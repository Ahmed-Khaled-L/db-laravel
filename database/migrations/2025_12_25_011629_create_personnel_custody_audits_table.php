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
        Schema::create("personnel_custody_audits", function (Blueprint $table) {
            $table
                ->foreignId("id")
                ->primary()
                ->constrained("custody_audit_bases")
                ->onDelete("cascade");
            $table->foreignId("employee_id")->constrained("employees");
            $table->integer("quantity");
            $table->unsignedBigInteger("category_id");
            $table->string("category_type");
            $table->timestamps();

            $table
                ->foreign(["category_id", "category_type"])
                ->references(["id", "type"])
                ->on("categories");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("personnel_custody_audits");
    }
};
