# DirectAdmin Backup Automation (Demo)

This is a **demo project** that showcases how I would structure a small
backup automation system for hosting accounts (e.g. DirectAdmin accounts).

The goal of this repository is to demonstrate:

- Backend design in **PHP**
- Separation of concerns (client, backup manager, logger)
- Basic CLI tooling for automation
- How I think about backups, retention, and restore workflows

> ⚠️ This project does **not** contain any real customer data or production code.
> It uses a fake "DirectAdminClient" and local folders under `storage/`.

---

## Features

- Backup individual accounts to timestamped `.tar.gz` files
- List existing backups
- Restore an account from a backup archive
- Simple retention policy (delete backups older than N days)
- File-based logging

---

## Project Structure

- `config.example.php` – base configuration (paths, retention days, etc.)
- `src/BackupManager.php` – main backup/restore logic
- `src/DirectAdminClient.php` – demo client that simulates fetching account data
- `src/Logger.php` – simple file logger
- `scripts/backup.php` – CLI entrypoint to run backups
- `scripts/restore.php` – CLI entrypoint to restore backups
- `scripts/list_backups.php` – list available backups
- `storage/accounts` – mock accounts (project uses these as "DirectAdmin accounts")
- `storage/backups` – generated backup archives
- `storage/logs/app.log` – log file

---

## Getting Started

```bash
git clone https://github.com/<tu-usuario>/directadmin-backup-automation-demo.git
cd directadmin-backup-automation-demo
cp config.example.php config.php
mkdir -p storage/accounts storage/backups storage/logs

Create a test account:

mkdir -p storage/accounts/demo-account
echo "Hello from demo account" > storage/accounts/demo-account/index.html


Run a backup:

php scripts/backup.php --account=demo-account


List backups:

php scripts/list_backups.php


Restore a backup:

php scripts/restore.php --account=demo-account --file=<backup-file.tar.gz>

Example Cron Job

Run a daily backup for demo-account at 03:00:

0 3 * * * php /path/to/project/scripts/backup.php --account=demo-account >> /path/to/project/storage/logs/cron.log 2>&1

Notes

This project is a demo only and is intentionally simplified.

In a real environment I’ve worked with:

DirectAdmin and other panels

Remote storage (S3, SSH, NFS)

More advanced retention and monitoring


---

## ⚙ `config.example.php`

```php
<?php

return [
    // Root paths
    'accounts_root' => __DIR__ . '/storage/accounts',
    'backups_root'  => __DIR__ . '/storage/backups',
    'log_file'      => __DIR__ . '/storage/logs/app.log',

    // Retention in days (backups older than this may be deleted)
    'retention_days' => 7,

    // Fake DirectAdmin API settings (for demo only)
    'directadmin' => [
        'enabled' => false,
        'host'    => 'https://directadmin.example.com:2222',
        'user'    => 'demo',
        'token'   => 'demo-token',
    ],
];
