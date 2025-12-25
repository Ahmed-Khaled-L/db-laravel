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
        Schema::create('store_item_mappings', function (Blueprint $table) {
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('item_id')->constrained('items');
            $table->unsignedBigInteger('category_id'); // Logic Link to Categories.id
            $table->timestamps();
            $table->primary(['store_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_item_mappings');
    }
};
