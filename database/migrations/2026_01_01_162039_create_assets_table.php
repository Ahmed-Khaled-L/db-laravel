<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            // "مسلسل" from PDF (We treat it as the Primary Key)
            $table->integer('id')->primary();
            // "البيان"
            $table->string('name');
            // "القيمة" (nullable because some rows in PDF have no value)
            $table->decimal('value', 15, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
