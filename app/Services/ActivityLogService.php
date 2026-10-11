<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogService
{
    public function log(
        string $activity,
        string $description,
        ?int $userId = null,
        ?Request $request = null
    ): ActivityLog {

        $request ??= request();

        return ActivityLog::create([
            'user_id' => $userId ?? auth()->id(),
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    public function login(int $userId): ActivityLog
    {
        return $this->log(
            'login',
            'Pengguna berhasil login ke sistem.',
            $userId
        );
    }

    public function logout(?int $userId = null): ActivityLog
    {
        return $this->log(
            'logout',
            'Pengguna keluar dari sistem.',
            $userId
        );
    }

    public function register(int $userId): ActivityLog
    {
        return $this->log(
            'register',
            'Masyarakat melakukan registrasi akun.',
            $userId
        );
    }

    public function verifyAccount(
        int $masyarakatId,
        int $staffId
    ): ActivityLog {
        return $this->log(
            'verify_account',
            "Staff memverifikasi akun masyarakat dengan ID {$masyarakatId}.",
            $staffId
        );
    }

    public function submitApplication(
        int $pengajuanId,
        int $userId
    ): ActivityLog {
        return $this->log(
            'submit_application',
            "Masyarakat membuat pengajuan surat dengan ID {$pengajuanId}.",
            $userId
        );
    }

    public function verifyApplication(
        int $pengajuanId,
        int $staffId
    ): ActivityLog {
        return $this->log(
            'verify_application',
            "Staff memverifikasi pengajuan surat dengan ID {$pengajuanId}.",
            $staffId
        );
    }

    public function rejectApplication(
        int $pengajuanId,
        int $userId
    ): ActivityLog {
        return $this->log(
            'reject_application',
            "Pengajuan surat dengan ID {$pengajuanId} ditolak.",
            $userId
        );
    }

    public function signLetter(
        int $suratId,
        int $lurahId
    ): ActivityLog {
        return $this->log(
            'sign_letter',
            "Lurah melakukan tanda tangan digital pada surat dengan ID {$suratId}.",
            $lurahId
        );
    }

    public function downloadLetter(
        int $suratId,
        int $userId
    ): ActivityLog {
        return $this->log(
            'download_letter',
            "Pengguna mengunduh surat dengan ID {$suratId}.",
            $userId
        );
    }
}