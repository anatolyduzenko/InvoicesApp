<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'number' => Invoice::generateInvoiceNumber(),
            'status' => $this->faker->randomElement(['draft', 'new']),
            'issue_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'company_id' => Company::factory(),
            'account_id' => Account::factory(),
            'customer_id' => Customer::factory(),
            'total_amount' => $this->faker->randomFloat(2, 100, 5000),
        ];
    }
}
