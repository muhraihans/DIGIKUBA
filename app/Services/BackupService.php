<?php

namespace App\Services;

use App\Models\Backup;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class BackupService
{
    public function createDatabaseBackup(
        ?int $year = null
    ): Backup {

        $year ??= now()->year;

        $backupDirectory =
            storage_path(
                'app/backups/' . $year
            );

        if (!File::exists($backupDirectory)) {
            File::makeDirectory(
                $backupDirectory,
                0755,
                true
            );
        }

        $filename =
            'database-' .
            $year .
            '-' .
            now()->format('Y-m-d-His') .
            '.sql';

        $fullPath =
            $backupDirectory .
            DIRECTORY_SEPARATOR .
            $filename;

        $host =
            config('database.connections.mysql.host');

        $port =
            config('database.connections.mysql.port');

        $database =
            config('database.connections.mysql.database');

        $username =
            config('database.connections.mysql.username');

        $password =
            config('database.connections.mysql.password');

        $command =
            sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($fullPath)
            );

        exec(
            $command,
            $output,
            $result
        );

        if (
            $result !== 0 ||
            !File::exists($fullPath)
        ) {
            throw new RuntimeException(
                'Gagal membuat backup database.'
            );
        }

        $size =
            File::size($fullPath);

        return Backup::create([
            'tahun' => $year,
            'nama_file' => $filename,
            'path' => 'backups/' .
                $year .
                '/' .
                $filename,
            'ukuran' => $size,
        ]);
    }

    public function deleteBackup(
        Backup $backup
    ): bool {

        $fullPath =
            storage_path(
                'app/' . $backup->path
            );

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }

        return $backup->delete();
    }

    public function getBackupPath(
        Backup $backup
    ): string {

        return storage_path(
            'app/' . $backup->path
        );
    }
}