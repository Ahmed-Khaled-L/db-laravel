<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Make store_id nullable in register_pages
        Schema::table("register_pages", function (Blueprint $table) {
            // We drop the foreign key first to modify the column
            $table->dropForeign(["store_id"]);
            $table->unsignedBigInteger("store_id")->nullable()->change();

            // Re-add the constraint (optional, if you want it to still reference stores when not null)
            $table->foreign("store_id")->references("id")->on("stores");
        });

        // 2. Add notes to custody_audit_bases
        Schema::table("custody_audit_bases", function (Blueprint $table) {
            $table->text("notes")->nullable()->after("audit_type");
        });
    }

    public function down(): void
    {
        Schema::table("register_pages", function (Blueprint $table) {
            $table->dropForeign(["store_id"]);
            $table->unsignedBigInteger("store_id")->nullable(false)->change();
            $table->foreign("store_id")->references("id")->on("stores");
        });

        Schema::table("custody_audit_bases", function (Blueprint $table) {
            $table->dropColumn("notes");
        });
    }
};
