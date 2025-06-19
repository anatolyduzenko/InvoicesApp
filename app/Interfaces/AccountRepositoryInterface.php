<?php

namespace App\Interfaces;

use App\Models\Account;
use Illuminate\Pagination\LengthAwarePaginator;

interface AccountRepositoryInterface
{
    public function store(array $data): Account;

    public function update(Account $account, array $data): Account;

    public function delete(Account $account): void;

    public function getAll(array $filters = [], array $with = []): LengthAwarePaginator;
}
