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
        Schema::create('register_pages', function (Blueprint $table) {
            $table->foreignId('register_id')->constrained('registers');
            $table->integer('page_number');
            $table->foreignId('store_id')->constrained('stores');
            $table->timestamps();
            $table->primary(['register_id', 'page_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_pages');
    }
};
