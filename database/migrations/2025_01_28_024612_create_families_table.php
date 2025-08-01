<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('family', function (Blueprint $table) {
            $table->id();
            $table->string('family_code');
            $table->string('phone');   
            $table->string('subscription');

            $table->string('status')->default('active');
            $table->string('address')->nullable();
            $table->timestamps();
            

    
        });
        
        
        
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family');
    }
};