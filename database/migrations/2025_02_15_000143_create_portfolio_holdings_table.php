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
        Schema::create('portfolio_holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('average_cost', 10, 2);
            $table->decimal('total_investment', 15, 2);
            $table->timestamps();
            $table->unique(['portfolio_id', 'stock_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_holdings');
    }
};
