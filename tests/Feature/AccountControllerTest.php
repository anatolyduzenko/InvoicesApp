<?php

namespace Tests\Feature\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        Session::start();
    }

    public function test_index_displays_accounts_list()
    {
        Account::factory()->count(3)->create();

        $response = $this->get(route('accounts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('accounts/List')
            ->has('accounts.data', 3)
        );
    }

    public function test_create_displays_account_creation_form()
    {
        $response = $this->get(route('accounts.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('accounts/Edit')
        );
    }

    public function test_store_creates_account_and_redirects()
    {
        $data = [
            'intermediary' => 'InterBank',
            'institution' => 'Finance Inc.',
            'beneficiary' => 'David Allen',
            'account' => 'US123BANK0001',
            'currency' => 'USD',
            '_token' => csrf_token(),
        ];

        $response = $this->post(route('accounts.store'), $data);

        $response->assertRedirect(route('accounts.index'));
        $this->assertDatabaseHas('accounts', ['intermediary' => 'Interbank']);
    }

    public function test_show_displays_account_details()
    {
        $account = Account::factory()->create();

        $response = $this->get(route('accounts.show', $account));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('accounts/View')
            ->where('account.id', $account->id)
        );
    }

    public function test_edit_displays_account_edit_form()
    {
        $account = Account::factory()->create();

        $response = $this->get(route('accounts.edit', $account));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('accounts/Edit')
            ->where('account.id', $account->id)
        );
    }

    public function test_update_modifies_account_and_redirects()
    {
        $account = Account::factory()->create(['intermediary' => 'Old Name']);

        $response = $this->put(route('accounts.update', $account), [
            'intermediary' => 'InterBank',
            'institution' => 'Finance Inc.',
            'beneficiary' => 'David Allen',
            'account' => 'US123BANK0001',
            'currency' => 'USD',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('accounts.show', $account));
        $this->assertDatabaseHas('accounts', ['intermediary' => 'InterBank']);
    }

    public function test_destroy_deletes_account_and_redirects()
    {
        $account = Account::factory()->create();

        $response = $this->delete(route('accounts.destroy', [
            'account' => $account,
            '_token' => csrf_token(),
        ]));

        $response->assertRedirect(route('accounts.index'));
        $this->assertSoftDeleted('accounts', ['id' => $account->id]);
    }
}
