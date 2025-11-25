<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LeaveRequest as LeaveRequestModel;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;


#[Layout('layouts.app')]
#[Title('Pengajuan - Sajadadir')]
class LeaveRequest extends Component
{
    public $showModal = false;

    #[Validate('required|date|after_or_equal:today')]
    public $start_date = '';

    #[Validate('required|date|after_or_equal:start_date')]
    public $end_date = '';

    #[Validate('required|min:5|max:500')]
    public $reason = '';

    public $leaveRequests = [];
    public $stats = [];

    protected $messages = [
        'start_date.required' => 'Tanggal mulai harus diisi',
        'start_date.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini',
        'end_date.required' => 'Tanggal selesai harus diisi',
        'end_date.after_or_equal' => 'Tanggal selesai tidak boleh kurang dari tanggal mulai',
        'reason.required' => 'Alasan harus diisi',
        'reason.min' => 'Alasan minimal 5 karakter',
        'reason.max' => 'Alasan maksimal 500 karakter',
    ];

    public function mount()
    {
        // Check if user has karyawan role
        if (!auth()->user()->hasRole('karyawan')) {
            abort(403, 'Unauthorized');
        }

        $this->loadLeaveRequests();
        $this->loadStats();

        // Set default dates
        $this->start_date = Carbon::today()->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
    }

    public function loadLeaveRequests()
    {
        $this->leaveRequests = LeaveRequestModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'reason' => $request->reason,
                    'status' => $request->status,
                    'status_label' => $this->getStatusLabel($request->status),
                    'status_class' => $this->getStatusClass($request->status),
                    'duration' => $request->start_date->diffInDays($request->end_date) + 1,
                    'created_at' => $request->created_at,
                ];
            });
    }

    public function loadStats()
    {
        $requests = LeaveRequestModel::where('user_id', auth()->id())->get();

        $this->stats = [
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'approved')->count(),
            'rejected' => $requests->where('status', 'rejected')->count(),
        ];
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->start_date = Carbon::today()->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
        $this->reason = '';
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['start_date', 'end_date', 'reason']);
        $this->resetValidation();
    }

    public function submit()
    {
        $this->validate();

        LeaveRequestModel::create([
            'user_id' => auth()->id(),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        $this->loadLeaveRequests();
        $this->loadStats();
        $this->closeModal();

        session()->flash('success', 'Pengajuan berhasil dikirim!');
    }

    private function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    private function getStatusClass($status)
    {
        return match ($status) {
            'pending' => 'bg-warning-secondary text-warning-pressed',
            'approved' => 'bg-success-secondary text-success-main',
            'rejected' => 'bg-danger-secondary text-danger-main',
            default => 'bg-custom-gray-30 text-custom-gray-60',
        };
    }

    public function render()
    {
        return view('livewire.leave-request');
    }
}
