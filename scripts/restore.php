<?php

require __DIR__ . '/../vendor/autoload.php';

use App\BackupManager;
use App\DirectAdminClient;
use App\Logger;

$config = require __DIR__ . '/../config.php';

$options = getopt('', ['account:', 'file:']);
$account = $options['account'] ?? null;
$file    = $options['file'] ?? null;

if (!$account || !$file) {
    echo "Usage: php restore.php --account=<account-name> --file=<backup-file>" . PHP_EOL;
    exit(1);
}

$logger  = new Logger($config['log_file']);
$client  = new DirectAdminClient($config['accounts_root']);
$manager = new BackupManager(
    $client,
    $logger,
    $config['backups_root'],
    $config['retention_days']
);

try {
    $manager->restore($account, $file);
    echo "Restore completed for account '{$account}'." . PHP_EOL;
} catch (Throwable $e) {
    $logger->error($e->getMessage());
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
