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
        Schema::create('maintenance_items', function (Blueprint $table) {
            $table->foreignId('maintenance_id')->constrained('maintenance')->onDelete('cascade');
            $table->string('item_serial_no');
            $table->foreign('item_serial_no')->references('serial_no')->on('item_details');
            $table->primary(['maintenance_id', 'item_serial_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_items');
    }
};
