<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class SiswaImport implements ToCollection
{
    protected string $defaultPassword;

    public function __construct(string $defaultPassword = 'SmkTirtamulya2026')
    {
        $this->defaultPassword = $defaultPassword;
    }

    public function collection(Collection $rows)
    {
        $header = true;
        foreach ($rows as $index => $row) {
            if ($header) {
                $header = false;
                continue;
            }

            $nisn = isset($row[0]) ? trim((string) $row[0]) : null;
            $nama = isset($row[1]) ? trim((string) $row[1]) : null;
            $email = isset($row[2]) ? trim((string) $row[2]) : null;
            $hp = isset($row[3]) ? trim((string) $row[3]) : null;

            if (empty($nama)) {
                continue;
            }

            if (empty($email)) {
                $identifier = !empty($nisn) ? $nisn : Str::slug($nama);
                $email = "{$identifier}@siswa.smk.sch.id";
            }

            User::updateOrCreate(
                ['email' => $email],
                [
                    'nama' => $nama,
                    'nisn' => $nisn,
                    'hp' => $hp,
                    'role' => User::ROLE_ANGGOTA,
                    'status' => 1,
                    'password' => Hash::make($this->defaultPassword),
                ]
            );
        }
    }
}
