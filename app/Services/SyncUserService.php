<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class SyncUserService
{
    public function syncByNip(string $nip): ?User
    {
        $localUser = User::where('nip', $nip)->first();

        $apiUrl = config('services.sync_user.url');
        if (!$apiUrl) {
            return $localUser;
        }

        $page = 1;
        $lastPage = 1;

        do {
            try {
                $response = Http::timeout(10)
                    ->withoutVerifying()
                    ->get($apiUrl, ['page' => $page]);

                if ($response->failed()) {
                    break;
                }

                $pegawaiList = $response->json('data');
                $pagination = $response->json('pagination');
                $lastPage = $pagination['last_page'] ?? 1;

                if (!is_array($pegawaiList)) {
                    break;
                }

                foreach ($pegawaiList as $pegawai) {
                    if (($pegawai['nip'] ?? '') === $nip) {
                        return $this->createOrUpdateUser($pegawai);
                    }
                }

                $page++;
            } catch (\Exception $e) {
                break;
            }
        } while ($page <= $lastPage);

        return $localUser;
    }

    public function syncAll(): array
    {
        $synced = 0;
        $errors = 0;

        $apiUrl = config('services.sync_user.url');
        if (!$apiUrl) {
            return ['synced' => 0, 'errors' => 0, 'message' => 'URL API tidak dikonfigurasi'];
        }

        try {
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->get($apiUrl, ['page' => 1]);

            if ($response->failed()) {
                return ['synced' => 0, 'errors' => 0, 'message' => 'Gagal terhubung ke API'];
            }

            $pagination = $response->json('pagination');
            $lastPage = $pagination['last_page'] ?? 1;

            $pegawaiList = $response->json('data');
            if (is_array($pegawaiList)) {
                foreach ($pegawaiList as $pegawai) {
                    try {
                        $this->createOrUpdateUser($pegawai);
                        $synced++;
                    } catch (\Exception $e) {
                        $errors++;
                    }
                }
            }

            for ($page = 2; $page <= $lastPage; $page++) {
                $response = Http::timeout(15)
                    ->withoutVerifying()
                    ->get($apiUrl, ['page' => $page]);

                if ($response->failed()) {
                    $errors += ($lastPage - $page + 1) * 20;
                    break;
                }

                $pegawaiList = $response->json('data');
                if (!is_array($pegawaiList)) {
                    break;
                }

                foreach ($pegawaiList as $pegawai) {
                    try {
                        $this->createOrUpdateUser($pegawai);
                        $synced++;
                    } catch (\Exception $e) {
                        $errors++;
                    }
                }
            }

        } catch (\Exception $e) {
            return ['synced' => $synced, 'errors' => $errors, 'message' => $e->getMessage()];
        }

        return [
            'synced' => $synced,
            'errors' => $errors,
            'message' => "Sukses sync $synced pegawai" . ($errors ? ", $errors gagal" : ''),
        ];
    }

    private function createOrUpdateUser(array $pegawai): User
    {
        $nip = $pegawai['nip'] ?? '';
        $nama = $pegawai['nama'] ?? $nip;
        $jabatan = $pegawai['jabatan'] ?? null;

        $existingUser = User::where('nip', $nip)->first();

        if ($existingUser) {
            $existingUser->update([
                'name' => $nama,
                'jabatan' => $jabatan,
            ]);

            if (!$existingUser->roles()->exists()) {
                $existingUser->assignRole('User');
            }

            return $existingUser;
        }

        $roleName = $this->determineRole($pegawai);

        $user = User::create([
            'name' => $nama,
            'email' => $nip . '@hamora.local',
            'password' => $nip,
            'nip' => $nip,
            'jabatan' => $jabatan,
            'is_active' => true,
            'email_verified_at' => now(),
            'must_change_password' => true,
        ]);

        $user->assignRole($roleName);

        return $user;
    }

    private function determineRole(array $pegawai): string
    {
        $superAdminNips = ['198706072020121003'];

        if (in_array($pegawai['nip'] ?? '', $superAdminNips)) {
            return 'Super Admin';
        }

        $roleInfo = $pegawai['role_info'] ?? null;
        if ($roleInfo && isset($roleInfo['roles']) && is_array($roleInfo['roles'])) {
            $sijagaRoles = $roleInfo['roles'];
            if (in_array('super_admin', $sijagaRoles) || in_array('Super Admin', $sijagaRoles)) {
                return 'Super Admin';
            }
            if (in_array('admin', $sijagaRoles) || in_array('Admin', $sijagaRoles)) {
                return 'Admin';
            }
        }

        return 'User';
    }
}
