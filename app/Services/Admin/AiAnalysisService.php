<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\EmotionalCheckin;
use Carbon\Carbon;

class AiAnalysisService
{
    protected string $openAiKey;
    protected string $openAiProjectId;
    protected string $googleAiKey;

    public function __construct()
    {
        $this->openAiKey = env('OPENAI_API_KEY', '');
        $this->openAiProjectId = env('OPENAI_PROJECT_ID', '');
        $this->googleAiKey = env('GOOGLE_AI_API_KEY', '');
    }

    /**
     * 🔹 Analisis harian (berdasarkan check-in terbaru)
     */
    public function analyzeDaily(string $mood, ?string $note): string
    {
        $prompt = <<<PROMPT
You are an empathetic AI assistant.
Analyze today's emotional check-in and give a short, caring response.

Mood: {$mood}
Note: {$note}

Return valid JSON:
{
  "summary": "short mood summary",
  "predicted_risk": "low|medium|high",
  "ai_message": "one or two supportive sentences"
}
PROMPT;

        $responseText = $this->callOpenAi($prompt);

        if ($this->isFailure($responseText)) {
            $responseText = $this->callGemini($prompt);
        }

        $clean = trim(preg_replace('/^```json|```$/m', '', $responseText));
        $decoded = json_decode($clean, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }

        return json_encode([
            'summary' => 'No data available.',
            'predicted_risk' => 'low',
            'ai_message' => $clean,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 🔍 Analisis historis (30 hari terakhir)
     */
    public function analyzeTrends(int $userId): string
    {
        $history = EmotionalCheckin::where('user_id', $userId)
            ->where('checked_in_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('checked_in_at', 'desc')
            ->get(['mood', 'internal_weather', 'energy_level', 'balance', 'note', 'checked_in_at']);

        $historyText = $history->isEmpty()
            ? 'No emotional history found for the last 30 days.'
            : $history->map(function ($h) {
                $moodText = is_array($h->mood) ? implode(', ', $h->mood) : $h->mood;
                $date = $h->checked_in_at instanceof \Illuminate\Support\Carbon
                    ? $h->checked_in_at->format('Y-m-d')
                    : (string)$h->checked_in_at;
                return "[{$date}] Mood: {$moodText}, Energy: {$h->energy_level}, Balance: {$h->balance}, Note: {$h->note}";
            })->implode("\n");

        $prompt = <<<PROMPT
You are an analytical and empathetic AI assistant.
Analyze the emotional history for the last 30 days and summarize patterns.

Data:
{$historyText}

Return valid JSON:
{
  "trend_summary": "overall trend description",
  "predicted_risk": "low|medium|high",
  "ai_message": "short supportive message"
}
PROMPT;

        $responseText = $this->callOpenAi($prompt);

        if ($this->isFailure($responseText)) {
            Log::warning('OpenAI failed, falling back to Gemini...');
            $responseText = $this->callGemini($prompt);
        }

        $clean = trim(preg_replace('/^```json|```$/m', '', $responseText));
        $decoded = json_decode($clean, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }

        return json_encode([
            'trend_summary' => 'No trend data available.',
            'predicted_risk' => 'low',
            'ai_message' => $clean,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 🔧 Helper untuk cek kegagalan API
     */
    protected function isFailure(string $result): bool
    {
        $keywords = ['Gagal', 'Failed', 'Error', 'quota', 'limit', 'exception'];
        foreach ($keywords as $word) {
            if (stripos($result, $word) !== false) return true;
        }
        return false;
    }

    public function analyzeMood(string $mood, ?string $note, int $userId): string
    {
        $prompt = <<<PROMPT
You are an empathetic AI assistant.
Analyze this user's emotional state and give a short JSON response.

User ID: {$userId}
Mood: {$mood}
Note: {$note}

Return valid JSON:
{
  "summary": "short description of user's mood",
  "predicted_risk": "low|medium|high",
  "ai_message": "one or two supportive sentences"
}
PROMPT;

        $responseText = $this->callOpenAi($prompt);

        if ($this->isFailure($responseText)) {
            $responseText = $this->callGemini($prompt);
        }

        $clean = trim(preg_replace('/^```json|```$/m', '', $responseText));
        $decoded = json_decode($clean, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }

        return json_encode([
            'summary' => 'No analysis available.',
            'predicted_risk' => 'low',
            'ai_message' => $clean,
        ], JSON_UNESCAPED_UNICODE);
    }


    /**
     * 🔹 Panggil OpenAI API
     */
    protected function callOpenAi(string $prompt): string
    {
        try {
            $response = Http::retry(2, 1500)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->openAiKey}",
                    'Content-Type' => 'application/json',
                    'OpenAI-Project' => $this->openAiProjectId,
                ])
                ->post('https://api.openai.com/v1/responses', [
                    'model' => 'gpt-4o-mini',
                    'input' => $prompt,
                    'temperature' => 0.6,
                ]);

            if ($response->failed()) {
                Log::error('OpenAI error', ['status' => $response->status(), 'body' => $response->body()]);
                return "Failed: OpenAI status {$response->status()}";
            }

            $data = $response->json();
            return $data['output'][0]['content'][0]['text'] ?? json_encode($data);
        } catch (\Throwable $e) {
            Log::error('OpenAI exception: ' . $e->getMessage());
            return "Failed: OpenAI exception {$e->getMessage()}";
        }
    }

    /**
     * 🔹 Panggil Gemini API (fallback)
     */
    protected function callGemini(string $prompt): string
    {
        try {
            $model = 'gemini-2.0-flash';
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->googleAiKey}";

            $response = Http::retry(2, 1500)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                ]);

            if ($response->failed()) {
                Log::error('Gemini error', ['status' => $response->status(), 'body' => $response->body()]);
                return "Failed: Gemini status {$response->status()}";
            }

            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? json_encode($data);
        } catch (\Throwable $e) {
            Log::error('Gemini exception: ' . $e->getMessage());
            return "Failed: Gemini exception {$e->getMessage()}";
        }
    }
}
