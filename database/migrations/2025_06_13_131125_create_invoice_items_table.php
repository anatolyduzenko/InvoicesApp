<?php

use App\Models\Invoice;
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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Invoice::class)
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('description', 255);
            $table->string('unit', 50)->nullable();
            $table->float('price', 2)->default(0.00);
            $table->float('qty', 2)->default(0.00);
            $table->float('amount', 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
