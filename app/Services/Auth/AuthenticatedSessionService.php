<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\HasHttpResponse;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Class AuthenticatedSessionService.
 */
class AuthenticatedSessionService
{
    use HasHttpResponse;

    public function store(mixed $data)
    {
        try {
            $data->authenticate();

            $user = $data->user();
            $remember = $data->boolean('remember');
            $tokens = $user->tokens()->where('name', 'web-api-token')->orderByDesc('created_at')->get();

            $user['is_remember'] = $remember;
            $user['refreshToken'] = Str::random(64);
            $user['token'] = $user->createToken('web-api-token', ['*'], $remember ? now()->addDays(7) : now()->addHours(12));
            PersonalAccessToken::where('id', $user['token']->accessToken->id)->update(['is_remember' => $remember]);

            if ($tokens->count() >= 3) {
                $tokensToDelete = $tokens->slice(2);
                $tokensToDelete->each->delete();
            }

            DB::table('personal_refresh_tokens')->insert([
                'user_id'    => $user->id,
                'personal_access_token_id' => $user['token']->accessToken->id,
                'token'      => hash('sha256', $user['refreshToken']),
                'expires_at' => $remember ? now()->addDays(14) : now()->addHours(24),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return $user;
        } catch (\Throwable $e) {
            $this->handleErrorCondition(true, 'Login failed. Please try again. Error: ' . $e->getMessage(), 500);
        }
    }

    public function refresh(array $data)
    {
        $hashed = hash('sha256', $data['refresh-token']);

        $stored = DB::table('personal_refresh_tokens')
            ->join('users', 'personal_refresh_tokens.user_id', '=', 'users.id')
            ->join('personal_access_tokens', 'personal_refresh_tokens.personal_access_token_id', '=', 'personal_access_tokens.id')
            ->where('personal_refresh_tokens.token', $hashed)
            ->whereNull('personal_refresh_tokens.revoked_at')
            ->select(
                'personal_refresh_tokens.expires_at',
                'personal_refresh_tokens.id as refresh_token_id',
                'personal_access_tokens.is_remember as remember',
                'users.id as user_id'
            )
            ->first();
        $this->handleErrorCondition(!$stored || strtotime($stored->expires_at) < time(), 'Invalid refresh token', 401);

        DB::table('personal_refresh_tokens')->where('id', $stored->refresh_token_id)->update([
            'revoked_at' => now()
        ]);

        $userModel = User::find($stored->user_id);

        $userModel['token'] = $userModel->createToken('web-api-token', ['*'],  $stored->remember ? now()->addDays(7) : now()->addHours(12));
        PersonalAccessToken::where('id', $userModel['token']->accessToken->id)->update(['is_remember' => $stored->remember]);
        $userModel['refreshToken'] = Str::random(64);

        DB::table('personal_refresh_tokens')->insert([
            'user_id'    => $stored->user_id,
            'personal_access_token_id' => $userModel['token']->accessToken->id,
            'token'      => hash('sha256', $userModel['refreshToken']),
            'expires_at' => $stored->remember ? now()->addDays(14) : now()->addHours(24),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $activeTokens = PersonalAccessToken::where('tokenable_id', $stored->user_id)
            ->where('tokenable_type', User::class)
            ->orderByDesc('created_at')
            ->get();

        if ($activeTokens->count() >= 3) {
            $tokensToDelete = $activeTokens->slice(3);
            foreach ($tokensToDelete as $token) {
                $token->delete();
            }
        }

        return $userModel;
    }
}
