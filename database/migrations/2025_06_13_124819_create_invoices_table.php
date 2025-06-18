<?php

use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number', 25);
            $table->string('status', 15)->default('new');
            $table->date('issue_date');
            $table->date('due_date');
            $table->float('total_amount', 2)->default(0.00);
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(Customer::class)->constrained();
            $table->foreignIdFor(Account::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
