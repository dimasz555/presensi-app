<div class="min-h-screen bg-custom-gray-20 pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-primary to-secondary text-white px-6 pt-6 pb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-2xl font-bold">Slip Gaji</h1>
                    <p class="text-white/70 text-sm">Riwayat Pembayaran</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 -mt-4">
        <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
            <label class="block text-sm font-medium text-custom-gray-90 mb-2">Tahun</label>
            <select wire:model.live="selectedYear"
                class="w-full px-4 py-3 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div class="space-y-4">
            @forelse($payrolls as $payroll)
                <div class="bg-custom-gray-10 rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-primary to-secondary text-custom-gray-10 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold">{{ $payroll->month_name }} {{ $payroll->period_year }}
                                </h3>
                                <p class="text-white/80 text-xs">Dikirim
                                    {{ $payroll->created_at->locale('id')->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-custom-gray-60">Gaji Pokok</span>
                                <span
                                    class="font-semibold text-custom-gray-100">Rp{{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                            </div>

                            @if ($payroll->total_bonus > 0)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-success-main">+ Bonus</span>
                                    <span
                                        class="font-semibold text-success-main">Rp{{ number_format($payroll->total_bonus, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            @if ($payroll->total_deductions > 0)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-danger-main">- Potongan</span>
                                    <span
                                        class="font-semibold text-danger-main">Rp{{ number_format($payroll->total_deductions, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="pt-3 border-t border-custom-gray-30">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-custom-gray-100">Gaji Bersih</span>
                                    <span
                                        class="font-bold text-lg text-primary">Rp{{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @if ($payroll->file_path)
                            <a href="{{ Storage::disk('public')->url($payroll->file_path) }}" target="_blank"
                                class="w-full bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Slip Gaji
                            </a>
                        @else
                            <div class="bg-warning-secondary border border-warning-pressed rounded-xl p-3 text-center">
                                <p class="text-warning-pressed text-sm font-medium">PDF belum tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <svg class="w-24 h-24 text-custom-gray-40 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-bold text-custom-gray-100 mb-2">Belum Ada Slip Gaji</h3>
                    <p class="text-custom-gray-60 text-sm mb-4">
                        Slip gaji untuk tahun {{ $selectedYear }} belum tersedia.
                    </p>
                    @if (count($years) > 1)
                        <p class="text-custom-gray-50 text-xs">Coba pilih tahun lain di filter di atas</p>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($payrolls->hasPages())
            <div class="mt-6">
                {{ $payrolls->links('components.pagination') }}
            </div>
        @endif
    </div>

    <!-- Bottom Navigation -->
    @include('components.bottom-nav')
</div>

@script
    <script>
        $wire.on('show-toast', (event) => {
            if (typeof showToast === 'function') {
                showToast(event.message, event.type);
            } else {
                console.error('showToast not found!');
                alert(event.message); // Fallback
            }
        });
    </script>
@endscript
