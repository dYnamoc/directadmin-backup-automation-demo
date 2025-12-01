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
