<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Add new JSON column for multiple days
            $table->json('days_of_week')->nullable()->after('day_of_week');
        });

        // Migrate existing single day data to JSON array
        $schedules = DB::table('schedules')->get();
        foreach ($schedules as $schedule) {
            if ($schedule->day_of_week) {
                DB::table('schedules')
                    ->where('id', $schedule->id)
                    ->update(['days_of_week' => json_encode([$schedule->day_of_week])]);
            }
        }

        // Drop the old column and rename the new one
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('day_of_week');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('days_of_week', 'day_of_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Add back the old enum column
            $table->enum('old_day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])->nullable()->after('day_of_week');
        });

        // Migrate JSON data back to single day (take first day)
        $schedules = DB::table('schedules')->get();
        foreach ($schedules as $schedule) {
            if ($schedule->day_of_week) {
                $days = json_decode($schedule->day_of_week, true);
                if (is_array($days) && !empty($days)) {
                    DB::table('schedules')
                        ->where('id', $schedule->id)
                        ->update(['old_day_of_week' => $days[0]]);
                }
            }
        }

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('day_of_week');
            $table->renameColumn('old_day_of_week', 'day_of_week');
        });
    }
};
