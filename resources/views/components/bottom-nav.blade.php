<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-custom-gray-30 px-6 py-3 max-w-md mx-auto shadow-2xl">
    <div class="flex items-center justify-around">

        <!-- Absensi -->
        <a href="/presensi" wire:navigate
            class="flex flex-col items-center space-y-1 px-4 py-2 rounded-xl
            {{ request()->is('presensi') ? 'text-primary bg-[var(--color-primary-secondary)]' : 'text-custom-gray-60' }} transition">

            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            <span class="text-xs font-medium">Presensi</span>
        </a>

        <!-- Pengajuan -->
        <a href="/pengajuan" wire:navigate
            class="flex flex-col items-center space-y-1 px-4 py-2 rounded-xl
            {{ request()->is('pengajuan') ? 'text-primary bg-[var(--color-primary-secondary)]' : 'text-custom-gray-60' }} transition">

            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>

            <span class="text-xs font-medium">Pengajuan</span>
        </a>

        <!-- Slip Gaji -->
        <a href="/slip-gaji" wire:navigate
            class="flex flex-col items-center space-y-1 px-4 py-2 rounded-xl
            {{ request()->is('slip-gaji') ? 'text-primary bg-[var(--color-primary-secondary)]' : 'text-custom-gray-60' }} transition">

            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l2-3h10l2 3" />
                <circle cx="16" cy="12" r="1.5" stroke-width="2" />
            </svg>

            <span class="text-xs font-medium">Slip Gaji</span>
        </a>

        <!-- Profil -->
        <a href="/profil" wire:navigate
            class="flex flex-col items-center space-y-1 px-4 py-2 rounded-xl
            {{ request()->is('profil') ? 'text-primary bg-[var(--color-primary-secondary)]' : 'text-custom-gray-60' }} transition">

            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>

            <span class="text-xs font-medium">Profil</span>
        </a>

    </div>
</nav>
