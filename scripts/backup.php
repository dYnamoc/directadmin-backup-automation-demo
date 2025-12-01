<?php

require __DIR__ . '/../vendor/autoload.php';

use App\BackupManager;
use App\DirectAdminClient;
use App\Logger;

$config = require __DIR__ . '/../config.php';

$options = getopt('', ['account:']);
$account = $options['account'] ?? null;

if (!$account) {
    echo "Usage: php backup.php --account=<account-name>" . PHP_EOL;
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
    $path = $manager->backup($account);
    echo "Backup created: {$path}" . PHP_EOL;
} catch (Throwable $e) {
    $logger->error($e->getMessage());
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
