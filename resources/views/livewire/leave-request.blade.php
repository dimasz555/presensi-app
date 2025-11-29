<div class="min-h-screen bg-custom-gray-20 pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-primary to-secondary text-white px-6 pt-6 pb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-2xl font-bold">Pengajuan</h1>
                    <p class="text-white/70 text-sm">Cuti & Izin</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 -mt-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                <div class="w-10 h-10 bg-warning-secondary rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-warning-pressed" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-2xl font-bold text-custom-gray-100">{{ $stats['pending'] }}</p>
                <p class="text-xs text-custom-gray-60">Menunggu</p>
            </div>

            <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                <div class="w-10 h-10 bg-success-secondary rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-success-main" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-2xl font-bold text-custom-gray-100">{{ $stats['approved'] }}</p>
                <p class="text-xs text-custom-gray-60">Disetujui</p>
            </div>

            <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                <div class="w-10 h-10 bg-danger-secondary rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-danger-main" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-2xl font-bold text-custom-gray-100">{{ $stats['rejected'] }}</p>
                <p class="text-xs text-custom-gray-60">Ditolak</p>
            </div>
        </div>

        <!-- Button Tambah Pengajuan -->
        <button wire:click="openModal"
            class="w-full bg-gradient-to-r from-primary to-secondary text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all mb-6 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Pengajuan Baru
        </button>

        <!-- Riwayat Pengajuan -->
        <div class="bg-white rounded-2xl shadow-lg p-5">
            <h2 class="text-lg font-bold text-custom-gray-100 mb-4">Riwayat Pengajuan</h2>

            <div class="space-y-3">
                @forelse($leaveRequests as $request)
                    <div class="border border-custom-gray-30 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="font-semibold text-custom-gray-100">
                                        {{ $request['start_date']->locale('id')->isoFormat('D MMM YYYY') }} -
                                        {{ $request['end_date']->locale('id')->isoFormat('D MMM YYYY') }}
                                    </p>
                                </div>
                                <p class="text-xs text-custom-gray-60 mb-2">
                                    {{ $request['duration'] }} hari • Diajukan
                                    {{ $request['created_at']->locale('id')->diffForHumans() }}
                                </p>
                            </div>
                            <span
                                class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $request['status_class'] }} whitespace-nowrap">
                                {{ $request['status_label'] }}
                            </span>
                        </div>

                        <div class="bg-custom-gray-20 rounded-lg p-3">
                            <p class="text-xs text-custom-gray-60 mb-1">Alasan:</p>
                            <p class="text-sm text-custom-gray-90">{{ $request['reason'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-20 h-20 text-custom-gray-40 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-custom-gray-60 font-medium mb-2">Belum ada pengajuan</p>
                        <p class="text-custom-gray-50 text-sm">Klik tombol di atas untuk membuat pengajuan baru</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengajuan -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
            x-data="{ show: @entangle('showModal') }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click.self="$wire.closeModal()">

            <div class="bg-white rounded-t-3xl sm:rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto"
                x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95">

                <div
                    class="sticky top-0 bg-white border-b border-custom-gray-30 px-6 py-4 flex items-center justify-between rounded-t-3xl sm:rounded-t-2xl">
                    <h3 class="text-xl font-bold text-custom-gray-100">Pengajuan Baru</h3>
                    <button wire:click="closeModal"
                        class="w-8 h-8 bg-custom-gray-20 rounded-lg flex items-center justify-center hover:bg-custom-gray-30 transition-colors">
                        <svg class="w-5 h-5 text-custom-gray-90" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="submit" class="p-6 space-y-5">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Tanggal Mulai <span class="text-danger-main">*</span>
                        </label>
                        <input type="date" id="start_date" wire:model="start_date"
                            class="w-full px-4 py-3 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            required>
                        @error('start_date')
                            <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Tanggal Selesai <span class="text-danger-main">*</span>
                        </label>
                        <input type="date" id="end_date" wire:model="end_date"
                            class="w-full px-4 py-3 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            required>
                        @error('end_date')
                            <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Alasan <span class="text-danger-main">*</span>
                        </label>
                        <textarea id="reason" wire:model="reason" rows="4"
                            class="w-full px-4 py-3 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"
                            placeholder="Jelaskan alasan pengajuan Anda..." required></textarea>
                        <div class="flex justify-between items-center mt-1">
                            @error('reason')
                                <p class="text-danger-main text-xs">{{ $message }}</p>
                            @else
                                <p class="text-custom-gray-50 text-xs">Minimal 5 karakter</p>
                            @enderror
                            <p class="text-custom-gray-50 text-xs">{{ strlen($reason) }}/500</p>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeModal"
                            class="flex-1 bg-custom-gray-30 text-custom-gray-90 font-semibold py-3.5 rounded-xl hover:bg-custom-gray-40 active:scale-[0.98] transition-all">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="flex-1 bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3.5 rounded-xl hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="submit">Kirim Pengajuan</span>
                            <span wire:loading wire:target="submit" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
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
