<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('billing_logs', function (Blueprint $table) {
            $table->id();
            $table->string('family_code');
            $table->string('subscription_plan');
            $table->decimal('base_amount', 8, 2);
            $table->decimal('additional_multiplier', 5, 2);
            $table->decimal('amount_due', 8, 2);
            $table->timestamps();

            });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('billing_logs');
    }
}