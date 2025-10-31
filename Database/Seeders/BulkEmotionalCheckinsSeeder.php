<?php

namespace Database\Seeders;

use League\Csv\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\EmotionalCheckin;
use App\Models\Role;
use Carbon\Carbon;

class BulkEmotionalCheckinsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $path = storage_path('app/imports/emotional_checkins.csv');

            if (!file_exists($path)) {
                $this->command->error("CSV file not found at: {$path}");
                return;
            }

            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(',');
            $data = collect($csv->getRecords());
            $this->command->info("Total rows valid: " . $data->count());

            // --- Role seeds ---
            $roles = [
                'teacher' => Role::firstOrCreate(['name' => 'Teacher', 'guard_name' => 'web']),
                'se_teacher' => Role::firstOrCreate(['name' => 'SE Teacher', 'guard_name' => 'web']),
                'staff' => Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']),
                'support_staff' => Role::firstOrCreate(['name' => 'Support Staff', 'guard_name' => 'web']),
                'director' => Role::firstOrCreate(['name' => 'Director', 'guard_name' => 'web']),
                'head_unit_sd' => Role::firstOrCreate(['name' => 'Head Unit SD', 'guard_name' => 'web']),
                'head_unit_jh' => Role::firstOrCreate(['name' => 'Head Unit JH', 'guard_name' => 'web']),
                'head_of_therapist' => Role::firstOrCreate(['name' => 'Head of Therapist', 'guard_name' => 'web']),
                'student' => Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']),
                'guest' => Role::firstOrCreate(['name' => 'Guest', 'guard_name' => 'web']),
            ];

            // --- Role mapping email list ---
            $teacherEmails = collect([
                'latifah@millennia21.id',
                'kholida@millennia21.id',
                'aria@millennia21.id',
                'hana.fajria@millennia21.id',
                'wina@millennia21.id',
                'sarahyuliana@millennia21.id',
                'hanny@millennia21.id',
                'dodi@millennia21.id',
                'faisal@millennia21.id',
                'abu@millennia21.id',
                'afiyanti.hardiansari@millennia21.id',
                'alinsuwisto@millennia21.id',
                'aprimaputri@millennia21.id',
                'belakartika@millennia21.id',
                'nana@millennia21.id',
                'devi.agriani@millennia21.id',
                'diya@millennia21.id',
                'fransiskaeva@millennia21.id',
                'gundah@millennia21.id',
                'hadi@millennia21.id',
                'himawan@millennia21.id',
                'alys@millennia21.id',
                'maria@millennia21.id',
                'nadiamws@millennia21.id',
                'nanda@millennia21.id',
                'nathasya@millennia21.id',
                'novia@millennia21.id',
                'widya@millennia21.id',
                'pipiet@millennia21.id',
                'cecil@millennia21.id',
                'putri.fitriyani@millennia21.id',
                'raisa@millennia21.id',
                'rifqi.satria@millennia21.id',
                'risma.angelita@millennia21.id',
                'risma.galuh@millennia21.id',
                'rizkinurul@millennia21.id',
                'robby.noer@millennia21.id',
                'triayulestari@millennia21.id',
                'triafadilla@millennia21.id',
                'vickiaprinando@millennia21.id',
                'yohana@millennia21.id',
                'yosafat@millennia21.id',
                'oudy@millennia21.id',
                'zolla@millennia21.id',
                'chaca@millennia21.id',
                'sisil@millennia21.id',
                'nayandra@millennia21.id'
            ]);

            $seTeacherEmails = collect([
                'dhaffa@millennia21.id',
                'almia@millennia21.id',
                'anggie@millennia21.id',
                'annisa@millennia21.id',
                'devilarasati@millennia21.id',
                'dien@millennia21.id',
                'akbarfadholi98@millennia21.id',
                'fasa@millennia21.id',
                'ferlyna.balqis@millennia21.id',
                'galen@millennia21.id',
                'iis@millennia21.id',
                'ikarahayu@millennia21.id',
                'kusumawantari@millennia21.id',
                'novan@millennia21.id',
                'prisy@millennia21.id',
                'restia.widiasari@millennia21.id',
                'rezarizky@millennia21.id',
                'rike@millennia21.id',
                'roma@millennia21.id',
                'salsabiladhiyaussyifa@millennia21.id',
                'tiastiningrum@millennia21.id',
                'vinka@millennia21.id'
            ]);

            $staffEmails = collect([
                'adibah.hana@millennia21.id',
                'wina@millennia21.id',
                'derry@millennia21.id',
                'aya@millennia21.id',
                'jo@millennia21.id',
                'maulida.yunita@millennia21.id',
                'made@millennia21.id',
                'novi@millennia21.id',
                'ismail@millennia21.id',
                'ratna@millennia21.id',
                'rain@millennia21.id',
                'susantika@millennia21.id',
                'hanny@millennia21.id',
                'ari.wibowo@millennia21.id',
                'sayed.jilliyan@millennia21.id',
                'kiki@millennia21.id',
                'ian.ahmad@millennia21.id',
                'andre@millennia21.id',
                'muhammad.farhan@millennia21.id'
            ]);

            $supportStaffEmails = collect([
                'abdul.mansyur@millennia21.id',
                'abdullah@millennia21.id',
                'adiya.herisa@millennia21.id',
                'dina@millennia21.id',
                'dona@millennia21.id',
                'irawan@millennia21.id',
                'khairul@millennia21.id',
                'sandi@millennia21.id',
                'fathan.qalbi@millennia21.id',
                'awal@millennia21.id',
                'ananta@millennia21.id',
                'mukron@millennia21.id',
                'nopi@millennia21.id',
                'robby@millennia21.id',
                'robiatul@millennia21.id',
                'rohmatulloh@millennia21.id',
                'udom@millennia21.id',
                'usep@millennia21.id',
                'yeti@millennia21.id',
                'danu@millennia21.id'
            ]);

            $directorEmail = 'mahrukh@millennia21.id';
            $headUnitSDEmail = 'kholida@millennia21.id';
            $headUnitJHEmail = 'aria@millennia21.id';
            $headTherapistEmail = 'hana.fajria@millennia21.id';

            $imported = 0;
            $skipped = 0;

            foreach ($data as $row) {
                $name = trim($row['Name:'] ?? $row['Name'] ?? '');
                if (empty($name)) {
                    $skipped++;
                    continue;
                }

                $email = strtolower(Str::slug($name, '.')) . '@school.local';
                if (isset($row['Email']) && filter_var($row['Email'], FILTER_VALIDATE_EMAIL)) {
                    $email = strtolower(trim($row['Email']));
                }

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'uuid' => (string) Str::uuid(),
                        'name' => $name,
                        'password' => bcrypt('password'),
                    ]
                );

                $roleName = $this->mapEmailToRole(
                    $email,
                    $teacherEmails,
                    $seTeacherEmails,
                    $staffEmails,
                    $supportStaffEmails,
                    $directorEmail,
                    $headUnitSDEmail,
                    $headUnitJHEmail,
                    $headTherapistEmail
                );
                $role = $roles[$roleName] ?? $roles['guest'];

                DB::table('model_has_roles')->updateOrInsert(
                    [
                        'model_type' => User::class,
                        'model_uuid' => $user->uuid,
                        'role_uuid' => $role->uuid,
                    ],
                    []
                );

                $checkin = new EmotionalCheckin();
                $checkin->id = (string) Str::uuid();
                $checkin->user_id = $user->uuid;
                $checkin->role = $role->name;

                $moodColumn = collect(array_keys($row))
                    ->first(fn($key) => str_contains($key, 'Today I am') && str_contains($key, 'Hari ini saya'));
                $checkin->mood = json_encode(explode(',', $row[$moodColumn] ?? ''));

                $checkin->note = $row["Give a few details as to why you feel that way."] ?? null;

                $checkin->internal_weather = $this->mapWeather(
                    $row["What is your internal weather report? (That is --  describe the type of weather are you experiencing internally.)"] ?? ''
                );

                $presenceKey = collect(array_keys($row))->first(fn($k) => str_contains($k, 'current presence'));
                $capacityKey = collect(array_keys($row))->first(fn($k) => str_contains($k, 'current capacity'));

                $checkin->presence_level = (int) ($row[$presenceKey] ?? 0);
                $checkin->capasity_level = (int) ($row[$capacityKey] ?? 0);

                $contactKey = collect(array_keys($row))
                    ->first(fn($k) => str_contains($k, 'It would be helpful'));
                $checkin->contact_id = $this->normalizeContact($row[$contactKey] ?? null);

                $checkin->checked_in_at = $this->parseDate($row['Timestamp'] ?? null);
                $checkin->energy_level = 'medium';
                $checkin->balance = 'balanced';
                $checkin->load = 'moderate';
                $checkin->readiness = 'somewhat_ready';
                $checkin->save();

                $imported++;
            }

            $this->command->info("✅ Import selesai. Diproses: {$imported}, dilewati: {$skipped}");
        });
    }

    private function mapEmailToRole(
        string $email,
        $teacherEmails,
        $seTeacherEmails,
        $staffEmails,
        $supportStaffEmails,
        $directorEmail,
        $headUnitSDEmail,
        $headUnitJHEmail,
        $headTherapistEmail
    ): string {
        $email = strtolower($email);

        if ($email === $directorEmail) return 'director';
        if ($email === $headUnitSDEmail) return 'head_unit_sd';
        if ($email === $headUnitJHEmail) return 'head_unit_jh';
        if ($email === $headTherapistEmail) return 'head_of_therapist';
        if ($teacherEmails->contains($email)) return 'teacher';
        if ($seTeacherEmails->contains($email)) return 'se_teacher';
        if ($staffEmails->contains($email)) return 'staff';
        if ($supportStaffEmails->contains($email)) return 'support_staff';

        return 'teacher';
    }

    private function mapWeather(string $text): string
    {
        $t = strtolower(trim($text));
        return match (true) {
            str_contains($t, 'sun') => 'sunny_clear',
            str_contains($t, 'rain') => 'light_rain',
            str_contains($t, 'cloud') => 'partly_cloudy',
            str_contains($t, 'storm') => 'thunderstorms',
            str_contains($t, 'fog') => 'foggy',
            default => 'sunny_clear',
        };
    }

    private function parseDate(?string $raw)
    {
        try {
            return Carbon::parse($raw);
        } catch (\Exception $e) {
            return now();
        }
    }

    private function normalizeContact(?string $value): ?string
    {
        if (!$value) return null;
        $v = strtolower(trim($value));
        $noNeed = [
            'no need',
            'no, i don\'t need',
            'no need, i\'m good~',
            'none for the current moment',
            'im ok',
            '-',
            'so far, my self 🤣',
            'i don\'t think anyone needs to check in with me'
        ];
        foreach ($noNeed as $n) {
            if (str_contains($v, strtolower($n))) {
                return 'No Need';
            }
        }
        $v = str_replace([' ,', ', ', '  '], [',', ',', ' '], $v);
        return ucwords($v);
    }
}
