<?php

// ============================================
// STEP 1: Create Command
// ============================================
// File: app/Console/Commands/AutoCheckoutCommand.php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCheckoutCommand extends Command
{
    protected $signature = 'attendance:auto-checkout';
    protected $description = 'Auto checkout karyawan yang lupa checkout';

    public function handle()
    {
        $this->info('Starting auto checkout process...');

        // Get kemarin (karena run di tengah malam untuk hari sebelumnya)
        $yesterday = Carbon::yesterday();

        // Find all attendances yang belum checkout
        $attendances = Attendance::whereDate('date', $yesterday)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->with('user')
            ->get();

        if ($attendances->isEmpty()) {
            $this->info('No attendances need auto checkout.');
            return 0;
        }

        $count = 0;

        foreach ($attendances as $attendance) {
            try {
                // Tentukan jam checkout otomatis (misal: jam kerja berakhir + 1 jam)
                $checkoutTime = $this->determineAutoCheckoutTime($attendance);

                $attendance->update([
                    'check_out' => $checkoutTime,
                    'check_out_lat' => null, // No location for auto checkout
                    'check_out_long' => null,
                    'auto_checkout' => true, 
                ]);

                $count++;
                $this->line("✓ Auto checkout: {$attendance->user->name} at {$checkoutTime}");
            } catch (\Exception $e) {
                $this->error("✗ Failed for user {$attendance->user->name}: {$e->getMessage()}");
            }
        }

        $this->info("Auto checkout completed: {$count} records processed.");

        return 0;
    }

    /**
     * Determine auto checkout time based on work schedule
     */
    private function determineAutoCheckoutTime(Attendance $attendance): Carbon
    {
        $date = Carbon::parse($attendance->date);
        $dayOfWeek = strtolower($date->format('l'));

        // Get work schedule for that day
        $schedule = WorkSchedule::where('work_day', $dayOfWeek)->first();

        if ($schedule) {
            // Set checkout 1 jam setelah jam kerja berakhir
            $endTime = Carbon::parse($schedule->work_end_time);
            return $date->setTimeFrom($endTime)->addHour();
        }

        // Default: checkout jam 18:00 jika tidak ada schedule
        return $date->setTime(18, 0, 0);
    }
}
