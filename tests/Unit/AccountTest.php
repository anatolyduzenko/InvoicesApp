<?php

namespace Tests\Unit;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_account()
    {
        $account = Account::create([
            'intermediary' => 'Bank Intermediary',
            'institution' => 'Test Institution',
            'beneficiary' => 'John Doe',
            'account' => 'GB00BANK123456789',
            'currency' => 'USD',
        ]);

        $this->assertDatabaseHas('accounts', [
            'account' => 'GB00BANK123456789',
            'beneficiary' => 'John Doe',
        ]);
    }

    public function test_it_soft_deletes_an_account()
    {
        $account = Account::create([
            'intermediary' => 'InterBank',
            'institution' => 'Finance Inc.',
            'beneficiary' => 'David Allen',
            'account' => 'US123BANK0001',
            'currency' => 'USD',
        ]);

        $account->delete();

        $this->assertSoftDeleted('accounts', [
            'id' => $account->id,
        ]);
    }
}
