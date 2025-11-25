<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-custom-gray-30 px-6 py-3 max-w-md mx-auto shadow-2xl">
    <div class="flex items-center justify-around">
        <!-- Absensi -->
        <a href="/presensi" wire:navigate
            class="flex flex-col items-center space-y-1 {{ request()->is('presensi') ? 'text-primary' : 'text-custom-gray-60' }} transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-xs font-medium">Presensi</span>
        </a>

        <!-- Pengajuan -->
        <a href="/pengajuan" wire:navigate
            class="flex flex-col items-center space-y-1 {{ request()->is('pengajuan') ? 'text-primary' : 'text-custom-gray-60' }} transition-colors">
            <svg class="w-6 h-6" fill="{{ request()->is('pengajuan') ? 'currentColor' : 'none' }}" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-xs font-medium">Pengajuan</span>
        </a>

        <!-- Riwayat -->
        <a href="/riwayat" wire:navigate
            class="flex flex-col items-center space-y-1 {{ request()->is('riwayat') ? 'text-primary' : 'text-custom-gray-60' }} transition-colors">
            <svg class="w-6 h-6" fill="{{ request()->is('riwayat') ? 'currentColor' : 'none' }}" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs font-medium">Riwayat</span>
        </a>

        <!-- Profil -->
        <a href="/profil" wire:navigate
            class="flex flex-col items-center space-y-1 {{ request()->is('profil') ? 'text-primary' : 'text-custom-gray-60' }} transition-colors">
            <svg class="w-6 h-6" fill="{{ request()->is('profil') ? 'currentColor' : 'none' }}" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-xs font-medium">Profil</span>
        </a>
    </div>
</nav>
