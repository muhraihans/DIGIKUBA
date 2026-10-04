<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * Daftar backup.
     */
    public function index()
    {
        $backups = Backup::latest()
            ->paginate(10);

        return view(
            'superadmin.backup.index',
            compact('backups')
        );
    }

    /**
     * Download backup.
     */
    public function download(
        Backup $backup
    ) {

        if (
            !Storage::disk('local')
                ->exists($backup->path)
        ) {
            return back()->with(
                'error',
                'File backup tidak ditemukan.'
            );
        }

        return Storage::disk('local')
            ->download(
                $backup->path,
                $backup->nama_file
            );
    }

    /**
     * Hapus backup.
     */
    public function destroy(
        Backup $backup
    ) {

        if (
            Storage::disk('local')
                ->exists($backup->path)
        ) {

            Storage::disk('local')
                ->delete($backup->path);
        }

        $backup->delete();

        return back()->with(
            'success',
            'Backup berhasil dihapus.'
        );
    }
}