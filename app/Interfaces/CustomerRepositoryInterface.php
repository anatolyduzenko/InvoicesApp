<?php

namespace App\Interfaces;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerRepositoryInterface
{
    public function store(array $data): Customer;

    public function update(Customer $customer, array $data): Customer;

    public function delete(Customer $customer): void;

    public function getAll(array $filters = [], array $with = []): LengthAwarePaginator;
}
