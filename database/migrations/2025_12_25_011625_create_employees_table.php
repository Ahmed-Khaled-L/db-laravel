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
        Schema::create("employees", function (Blueprint $table) {
            $table->id();
            $table->string("name");

            // Added nullable for Seeder compatibility (RealDataSeeder doesn't have SSNs yet)
            $table->string("ssn")->nullable();
            $table->string("job_title")->nullable();
            $table->date("birth_date")->nullable();

            // Add this line:
            $table
                ->foreignId("user_id")
                ->nullable()
                ->constrained("users")
                ->onDelete("cascade");

            $table->foreignId("department_id")->constrained("departments");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("employees");
    }
};
