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
        Schema::create("item_details", function (Blueprint $table) {
            $table->string("serial_no")->primary();
            $table->date("expiry_date")->nullable();
            $table
                ->foreignId("custody_audit_id")
                ->nullable()
                ->constrained("custody_audit_bases");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("item_details");
    }
};
