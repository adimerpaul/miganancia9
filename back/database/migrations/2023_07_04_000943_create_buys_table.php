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
        Schema::create('buys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('product_id')->constrained('products');
            $table->string('unit')->nullable()->default('Unidad');
            $table->string('description')->nullable();
            $table->string('unitQuantity')->nullable()->default('1');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->date('dateExpiration')->nullable();
            $table->string('canceled')->default('No');
            $table->string('canceledBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buys');
    }
};
