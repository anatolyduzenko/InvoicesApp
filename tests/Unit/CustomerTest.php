<?php

namespace Tests\Unit;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_customer()
    {
        $customer = Customer::create([
            'name' => 'Alice Johnson',
            'country' => 'USA',
            'address' => '123 Main St',
            'email' => 'alice@example.com',
            'phone' => '+1-555-1234',
            'currency' => 'USD',
            'template_name' => 'default',
        ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'alice@example.com',
            'name' => 'Alice Johnson',
        ]);
    }

    public function test_it_soft_deletes_a_customer()
    {
        $customer = Customer::create([
            'name' => 'Bob Smith',
            'country' => 'UK',
            'address' => '456 High St',
            'email' => 'bob@example.com',
            'phone' => '+44-7000-0000',
            'currency' => 'GBP',
            'template_name' => 'custom',
        ]);

        $customer->delete();

        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);
    }
}
