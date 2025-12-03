<?php

namespace App\Services;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PayrollPdfService
{
    public function generatePdf(Payroll $payroll): string
    {
        $payroll->load(['user.position', 'salaryComponents']);

        $data = [
            'payroll' => $payroll,
            'user' => $payroll->user,
            'position' => $payroll->user->position?->name ?? '-',
            'bonuses' => $payroll->bonuses()->get(),
            'deductions' => $payroll->deductions()->get(),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.payroll-slip', $data);

        $pdf->setPaper('a5', 'landscape');

        // Nama file
        $timestamp = now()->format('YmdHis');
        $fileName = 'slip-gaji-' . $payroll->user->name . '-' . $payroll->period_month . '-' . $payroll->period_year . '-' . $timestamp . '.pdf';
        $fileName = str_replace(' ', '-', strtolower($fileName));

        $path = 'payroll-slips/' . $payroll->period_year . '/' . $payroll->period_month;

        if (!empty($payroll->file_path)) {
            Storage::disk('public')->delete($payroll->file_path);
        }

        Storage::disk('public')->put($path . '/' . $fileName, $pdf->output());

        return $path . '/' . $fileName;
    }

    public function getPdfUrl(Payroll $payroll): ?string
    {
        if (empty($payroll->file_path)) {
            return null;
        }

        return Storage::disk('public')->url($payroll->file_path);
    }

    public function deletePdf(Payroll $payroll): bool
    {
        if (empty($payroll->file_path)) {
            return false;
        }

        return Storage::disk('public')->delete($payroll->file_path);
    }
}
