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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('staff_code');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');

            $table->string('age');
            // $table->string('grade_level');
            $table->string('email')->unique();
            $table->string('phone');
            $table->date('birth');
            $table->string('address');
            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
