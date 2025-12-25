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
        Schema::create('inventory_incidents', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('description');
            $table->decimal('cost', 10, 2);
            $table->string('type');
            $table->decimal('collected_cost', 10, 2)->default(0);
            $table->boolean('From_Government')->default(false);
            $table->boolean('From_Persons')->default(false);
            $table->foreignId('store_id')->constrained('stores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_incidents');
    }
};
