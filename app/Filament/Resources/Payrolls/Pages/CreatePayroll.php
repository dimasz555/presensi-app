<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;

    protected function afterCreate(): void
    {
        // Refresh record untuk memastikan data terbaru
        $payroll = $this->record->fresh();

        $user = User::find($payroll->user_id);

        if (!$user) {
            return;
        }

        $totalLateMinutes = $user->getTotalLateMinutesInMonth(
            (int) $payroll->period_month,
            (int) $payroll->period_year
        );

        $totalLatePenalty = $user->getTotalLatePenaltyInMonth(
            (int) $payroll->period_month,
            (int) $payroll->period_year
        );

        // Debug: Log untuk cek apakah method ini terpanggil
        \Illuminate\Support\Facades\Log::info('afterCreate Payroll', [
            'payroll_id' => $payroll->id,
            'user_id' => $user->id,
            'total_late_minutes' => $totalLateMinutes,
            'total_late_penalty' => $totalLatePenalty,
        ]);

        // Auto create salary component untuk denda keterlambatan
        if ($totalLatePenalty > 0) {
            $component = $payroll->salaryComponents()->create([
                'type' => 'deduction',
                'name' => "Denda Keterlambatan ({$totalLateMinutes} menit × Rp " . number_format($user->late_penalty_per_minute) . ")",
                'amount' => $totalLatePenalty,
            ]);

            \Illuminate\Support\Facades\Log::info('Salary Component Created', [
                'component_id' => $component->id,
            ]);

            // Recalculate payroll
            $payroll->recalculate();
        }
    }
}
