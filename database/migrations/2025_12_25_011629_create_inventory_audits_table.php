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
        Schema::create('inventory_audits', function (Blueprint $table) {
            $table->foreignId('id')->primary()->constrained('custody_audit_base')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores');
            $table->integer('observed_quantity');
            $table->integer('booked_quantity');
            $table->string('observed_state');
            $table->string('booked_state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_audits');
    }
};
