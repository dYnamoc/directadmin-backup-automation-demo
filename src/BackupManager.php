<?php

namespace App;

use RuntimeException;

class BackupManager
{
    public function __construct(
        private DirectAdminClient $client,
        private Logger $logger,
        private string $backupsRoot,
        private int $retentionDays = 7
    ) {}

    public function backup(string $account): string
    {
        if (!$this->client->accountExists($account)) {
            throw new RuntimeException("Account '{$account}' does not exist.");
        }

        $accountPath = $this->client->getAccountPath($account);

        if (!is_dir($this->backupsRoot)) {
            mkdir($this->backupsRoot, 0775, true);
        }

        $timestamp = date('Ymd_His');
        $filename  = sprintf('%s_%s.tar.gz', $account, $timestamp);
        $fullPath  = $this->backupsRoot . DIRECTORY_SEPARATOR . $filename;

        $this->logger->info("Starting backup for account '{$account}'.");

        // Simple tar.gz using system tar (for demo)
        $cmd = sprintf(
            "cd %s && tar -czf %s %s",
            escapeshellarg(dirname($accountPath)),
            escapeshellarg($fullPath),
            escapeshellarg(basename($accountPath))
        );

        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            $this->logger->error("Backup failed for '{$account}'. Command exit code: {$exitCode}");
            throw new RuntimeException("Backup failed for '{$account}'.");
        }

        $this->logger->info("Backup completed for '{$account}': {$filename}");

        $this->applyRetention();

        return $fullPath;
    }

    public function listBackups(): array
    {
        if (!is_dir($this->backupsRoot)) {
            return [];
        }

        $files = scandir($this->backupsRoot) ?: [];

        return array_values(array_filter($files, function ($file) {
            return str_ends_with($file, '.tar.gz');
        }));
    }

    public function restore(string $account, string $backupFile): void
    {
        $backupPath = $this->backupsRoot . DIRECTORY_SEPARATOR . $backupFile;

        if (!is_file($backupPath)) {
            throw new RuntimeException("Backup file '{$backupFile}' not found.");
        }

        $accountPath = $this->client->getAccountPath($account);
        if (!is_dir($accountPath)) {
            mkdir($accountPath, 0775, true);
        }

        $this->logger->info("Restoring backup '{$backupFile}' for account '{$account}'.");

        $cmd = sprintf(
            "cd %s && tar -xzf %s",
            escapeshellarg(dirname($accountPath)),
            escapeshellarg($backupPath)
        );

        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            $this->logger->error("Restore failed for '{$account}' from '{$backupFile}'. Exit code: {$exitCode}");
            throw new RuntimeException("Restore failed for '{$account}'.");
        }

        $this->logger->info("Restore completed for '{$account}' from '{$backupFile}'.");
    }

    private function applyRetention(): void
    {
        if ($this->retentionDays <= 0) {
            return;
        }

        $now = time();
        $cutoff = $now - ($this->retentionDays * 86400);

        foreach ($this->listBackups() as $file) {
            $path = $this->backupsRoot . DIRECTORY_SEPARATOR . $file;
            if (filemtime($path) < $cutoff) {
                $this->logger->info("Deleting old backup file: {$file}");
                @unlink($path);
            }
        }
    }
}
