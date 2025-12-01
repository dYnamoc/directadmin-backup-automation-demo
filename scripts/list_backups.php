<?php

require __DIR__ . '/../vendor/autoload.php';

use App\BackupManager;
use App\DirectAdminClient;
use App\Logger;

$config = require __DIR__ . '/../config.php';

$logger  = new Logger($config['log_file']);
$client  = new DirectAdminClient($config['accounts_root']);
$manager = new BackupManager(
    $client,
    $logger,
    $config['backups_root'],
    $config['retention_days']
);

$files = $manager->listBackups();

if (empty($files)) {
    echo "No backups found." . PHP_EOL;
    exit(0);
}

echo "Available backups:" . PHP_EOL;
foreach ($files as $file) {
    echo " - {$file}" . PHP_EOL;
}
