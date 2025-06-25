<?php

namespace App\Repositories;

use App\Interfaces\AccountRepositoryInterface;
use App\Models\Account;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class AccountRepository implements AccountRepositoryInterface
{
    /**
     * Stores a new account instance.
     */
    public function store(array $data): Account
    {
        return DB::transaction(function () use ($data) {
            return Account::create($data);
        });
    }

    /**
     * Updates account instance.
     */
    public function update(Account $account, array $data): Account
    {
        return DB::transaction(function () use ($account, $data) {
            $account->update($data);

            return $account;
        });
    }

    /**
     * Deletes instance.
     */
    public function delete(Account $account): void
    {
        DB::transaction(function () use ($account) {
            foreach ($account->invoices as $invoice) {
                (new InvoiceRepository)->delete($invoice);
            }
            $account->delete();
        });
    }

    /**
     * Retrieves a list of accounts with filters.
     */
    public function getAll(array $filters = [], array $with = [], $perPage = 20): LengthAwarePaginator
    {
        $query = Account::with($with)->latest();

        if (! empty($filters['with_trashed'])) {
            $query->withTrashed();
        }

        return $query->paginate($perPage);
    }
}
