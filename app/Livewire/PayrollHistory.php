<?php

namespace App\Livewire;

use App\Models\Payroll;
use App\Services\PayrollPdfService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Slip Gaji - Sajadadir')]

class PayrollHistory extends Component
{
    use WithPagination;

    public $selectedYear;
    public $years = [];
    public $perPage = 5; // Jumlah data per halaman

    public function mount()
    {
        // Check if user has karyawan role
        if (!auth()->user()->hasRole('karyawan')) {
            abort(403, 'Unauthorized');
        }

        $this->loadYears();
        $this->selectedYear = date('Y');
    }

    public function loadYears()
    {
        $this->years = Payroll::where('user_id', auth()->id())
            ->selectRaw('DISTINCT period_year')
            ->orderBy('period_year', 'desc')
            ->pluck('period_year')
            ->toArray();

        if (empty($this->years)) {
            $this->years = [date('Y')];
        }
    }

    public function updatedSelectedYear()
    {
        // Reset pagination saat ganti tahun
        $this->resetPage();
    }

    public function viewPdf($payrollId)
    {
        $payroll = Payroll::where('user_id', auth()->id())
            ->where('id', $payrollId)
            ->where('status', 'paid')
            ->firstOrFail();

        $pdfService = new PayrollPdfService();
        $url = $pdfService->getPdfUrl($payroll);

        if ($url) {
            // Redirect ke URL PDF (akan buka di tab baru via JavaScript)
            $this->dispatch('openPdfInNewTab', url: $url);
            return;
        }

        $this->dispatch(
            'show-toast',
            message: 'File tidak ditemukan!',
            type: 'error'
        );
    }

    public function render()
    {
        $payrolls = Payroll::where('user_id', auth()->id())
            ->where('period_year', $this->selectedYear)
            ->where('status', 'paid') // Hanya yang sudah dikirim
            ->orderBy('period_month', 'desc')
            ->paginate($this->perPage);
        return view('livewire.payroll-history', [
            'payrolls' => $payrolls
        ]);
    }
}
