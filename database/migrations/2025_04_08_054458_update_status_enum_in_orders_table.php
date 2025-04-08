<?php

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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['new', 'processing', 'shipped', 'delivered', 'cancelled'])
                  ->default('new')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['new', 'pending', 'processing', 'completed', 'declined', 'cancelled'])
                  ->default('new')
                  ->change();
        });
    }
};
