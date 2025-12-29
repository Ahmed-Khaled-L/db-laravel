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
        Schema::create("custody_audit_bases", function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->decimal("unit_price", 10, 2);
            $table->unsignedBigInteger("register_id");
            $table->integer("page_no");
            $table->foreignId("item_id")->constrained("items");
            $table->enum("audit_type", ["Personnel", "Inventory"]);
            $table->timestamps();

            // Composite FK
            $table
                ->foreign(["register_id", "page_no"])
                ->references(["register_id", "page_number"])
                ->on("register_pages");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("custody_audit_bases");
    }
};
