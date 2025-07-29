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
        Schema::table('billing_logs', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('billing_logs', 'status')) {
                $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            }
            if (!Schema::hasColumn('billing_logs', 'billing_date')) {
                $table->timestamp('billing_date')->nullable();
            }
            if (!Schema::hasColumn('billing_logs', 'due_date')) {
                $table->timestamp('due_date')->nullable();
            }
            if (!Schema::hasColumn('billing_logs', 'paid_date')) {
                $table->timestamp('paid_date')->nullable();
            }
            if (!Schema::hasColumn('billing_logs', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('billing_logs', 'student_count')) {
                $table->integer('student_count')->nullable();
            }
            if (!Schema::hasColumn('billing_logs', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_logs', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'billing_date',
                'due_date',
                'paid_date',
                'payment_method',
                'student_count',
                'notes'
            ]);
        });
    }
};
