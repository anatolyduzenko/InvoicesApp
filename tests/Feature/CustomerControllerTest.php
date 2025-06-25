<?php

namespace Tests\Feature\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        Session::start();
    }

    public function test_index_displays_customers_list()
    {
        Customer::factory()->count(3)->create();

        $response = $this->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('customers/List')
            ->has('customers.data', 3)
        );
    }

    public function test_create_displays_customer_creation_form()
    {
        $response = $this->get(route('customers.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('customers/Edit')
        );
    }

    public function test_store_creates_customer_and_redirects()
    {
        $data = [
            'name' => 'Test Customer',
            'country' => 'Canada',
            'address' => 'City, Test Street, 123',
            'email' => 'test@exmaple.com',
            'phone' => '+4562332232',
            'currency' => 'EUR',
            'template_name' => 'basic',
            '_token' => csrf_token(),
        ];

        $response = $this->post(route('customers.store'), $data);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', ['name' => 'Test Customer']);
    }

    public function test_show_displays_customer_details()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.show', $customer));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('customers/View')
            ->where('customer.id', $customer->id)
        );
    }

    public function test_edit_displays_customer_edit_form()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.edit', $customer));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('customers/Edit')
            ->where('customer.id', $customer->id)
        );
    }

    public function test_update_modifies_customer_and_redirects()
    {
        $customer = Customer::factory()->create(['name' => 'Old Name']);

        $response = $this->put(route('customers.update', $customer), [
            'name' => 'New Customer',
            'country' => 'Canada',
            'address' => 'City, Test Street, 123',
            'email' => 'test@exmaple.com',
            'phone' => '+4562332232',
            'template_name' => 'basic',
            'currency' => 'USD',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('customers.show', $customer));
        $this->assertDatabaseHas('customers', ['name' => 'New Customer']);
    }

    public function test_destroy_deletes_customer_and_redirects()
    {
        $customer = Customer::factory()->create();

        $response = $this->delete(route('customers.destroy', [
            'customer' => $customer,
            '_token' => csrf_token(),
        ]));

        $response->assertRedirect(route('customers.index'));
        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }
}
