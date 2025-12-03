<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateAbsentCommand extends Command
{
    /**
     * Command signature
     */
    protected $signature = 'attendance:generate-absent 
                            {--date= : Tanggal untuk generate absent (format: Y-m-d)}';

    /**
     * Command description
     */
    protected $description = 'Generate status alpha untuk karyawan yang tidak masuk tanpa keterangan';

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->info('Starting generate absent process...');

        // Get tanggal target (default: kemarin)
        $targetDate = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday();

        $this->info("Processing date: {$targetDate->format('Y-m-d')} ({$targetDate->format('l')})");

        // Check apakah hari tersebut adalah hari kerja
        if (!$this->isWorkDay($targetDate)) {
            $this->info('Not a work day. Skipping...');
            return Command::SUCCESS;
        }

        // Get all active employees (karyawan role)
        $employees = User::where('status', 'active')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'karyawan');
            })
            ->get();

        if ($employees->isEmpty()) {
            $this->warn('No active employees found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$employees->count()} active employees.");

        $alphaCount = 0;
        $skipCount = 0;

        // Progress bar
        $bar = $this->output->createProgressBar($employees->count());
        $bar->start();

        foreach ($employees as $employee) {
            try {
                // Check apakah sudah ada attendance
                $hasAttendance = Attendance::where('user_id', $employee->id)
                    ->whereDate('date', $targetDate)
                    ->exists();

                if ($hasAttendance) {
                    $skipCount++;
                    $bar->advance();
                    continue;
                }

                // Check apakah ada leave request yang approved
                $hasApprovedLeave = $this->hasApprovedLeave($employee->id, $targetDate);

                if ($hasApprovedLeave) {
                    $skipCount++;
                    $bar->advance();
                    continue;
                }

                // Generate attendance dengan status alpha
                Attendance::create([
                    'user_id' => $employee->id,
                    'date' => $targetDate,
                    'status' => 'alpha',
                    'check_in' => null,
                    'check_out' => null,
                    'check_in_lat' => null,
                    'check_in_long' => null,
                    'check_out_lat' => null,
                    'check_out_long' => null,
                    'face_matched' => false,
                    'face_confidence' => null,
                    'auto_checkout' => false,
                ]);

                $alphaCount++;

                // Log individual alpha record
                Log::channel('scheduler')->info('Alpha generated', [
                    'user_id' => $employee->id,
                    'user_name' => $employee->name,
                    'date' => $targetDate->format('Y-m-d'),
                ]);
            } catch (\Exception $e) {
                $this->error("\n✗ Failed for {$employee->name}: {$e->getMessage()}");
                Log::error('Generate absent error', [
                    'user_id' => $employee->id,
                    'user_name' => $employee->name,
                    'error' => $e->getMessage(),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info("┌─────────────────────────────────────┐");
        $this->info("│       Generate Absent Summary       │");
        $this->info("├─────────────────────────────────────┤");
        $this->info("│ Date: {$targetDate->format('Y-m-d')} ({$targetDate->format('l')})       │");
        $this->info("│ Total Employees: " . str_pad($employees->count(), 18) . "│");
        $this->info("│ Alpha Generated: " . str_pad($alphaCount, 18) . "│");
        $this->info("│ Skipped (Has Data): " . str_pad($skipCount, 14) . "│");
        $this->info("└─────────────────────────────────────┘");

        // Log summary
        Log::channel('scheduler')->info('Generate absent completed', [
            'date' => $targetDate->format('Y-m-d'),
            'total_employees' => $employees->count(),
            'alpha_generated' => $alphaCount,
            'skipped' => $skipCount,
        ]);

        return Command::SUCCESS;
    }

    /**
     * Check apakah tanggal tersebut adalah hari kerja
     */
    private function isWorkDay(Carbon $date): bool
    {
        // Get nama hari dalam bahasa Inggris lowercase (monday, tuesday, etc)
        $dayOfWeek = strtolower($date->format('l'));

        // Check di tabel work_schedules
        $workSchedule = WorkSchedule::where('work_day', $dayOfWeek)->first();

        if (!$workSchedule) {
            $this->warn("No work schedule found for {$dayOfWeek}");
            return false;
        }

        $this->info("Work schedule found: {$dayOfWeek} ({$workSchedule->work_start_time} - {$workSchedule->work_end_time})");

        return true;
    }

    /**
     * Check apakah user memiliki leave request yang approved
     */
    private function hasApprovedLeave(int $userId, Carbon $date): bool
    {
        return LeaveRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();
    }
}
