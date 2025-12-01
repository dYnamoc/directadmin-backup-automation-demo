<?php

namespace App;

/**
 * Demo "client" that simulates interacting with DirectAdmin.
 *
 * In this demo, accounts are just directories under storage/accounts.
 * In a real implementation, this class could wrap DirectAdmin's HTTP API.
 */
class DirectAdminClient
{
    public function __construct(
        private string $accountsRoot
    ) {}

    public function getAccountPath(string $account): string
    {
        return $this->accountsRoot . DIRECTORY_SEPARATOR . $account;
    }

    public function accountExists(string $account): bool
    {
        return is_dir($this->getAccountPath($account));
    }
}
