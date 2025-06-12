<?php

use App\Enums\Currency;
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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('intermediary', 255)->nullable();
            $table->string('institution', 255)->nullable();
            $table->string('beneficiary', 255)->nullable();
            $table->string('account', 100)->nullable();
            $table->enum('currency', array_column(Currency::cases(), 'value'))->default(Currency::USD->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
