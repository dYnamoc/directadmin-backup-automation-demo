
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
