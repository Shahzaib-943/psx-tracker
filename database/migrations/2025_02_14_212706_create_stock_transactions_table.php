<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->enum('transaction_type', ['buy', 'sell'])->default('buy');
            $table->unsignedInteger('quantity');
            $table->decimal('price_per_share', 15, 2);
            $table->decimal('gross_amount', 20, 2); // Total before deductions
            $table->decimal('broker_commission', 15, 2)->default(0);
            $table->decimal('final_price_per_share', 15, 4)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_amount', 20, 2); // Final after deductions
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
