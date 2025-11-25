<div class="min-h-screen flex flex-col bg-gradient-to-br from-primary to-secondary p-6">
    <!-- Logo Section -->
    <div class="flex-1 flex flex-col justify-center items-center text-white mb-8">
        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mb-4 shadow-lg">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" class="w-20 h-20">
        </div>
        <h1 class="text-3xl font-bold mb-2">Sajadadir</h1>
        <p class="text-white/80 text-center">Kelola kehadiran dengan mudah dan praktis</p>
    </div>

    <!-- Login Form Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-6 mb-6">
        <h2 class="text-2xl font-bold text-custom-gray-100 mb-6">Selamat Datang</h2>

        <form wire:submit.prevent="login">
            <!-- Email Input -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-custom-gray-90 mb-2">
                    Email
                </label>
                <div class="relative">
                    <input type="email" id="email" wire:model="email"
                        class="w-full px-4 py-3 pl-11 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        placeholder="nama@email.com" required>
                    <svg class="w-5 h-5 text-custom-gray-60 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                @error('email')
                    <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-custom-gray-90 mb-2">
                    Password
                </label>
                <div class="relative">
                    <input type="{{ $showPassword ? 'text' : 'password' }}" id="password" wire:model="password"
                        class="w-full px-4 py-3 pl-11 pr-11 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        placeholder="••••••••" required>
                    <svg class="w-5 h-5 text-custom-gray-60 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <button type="button" wire:click="$toggle('showPassword')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-custom-gray-60 hover:text-custom-gray-90 transition-colors">
                        @if ($showPassword)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        @endif
                    </button>
                </div>
                @error('password')
                    <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="remember"
                        class="w-4 h-4 text-primary bg-custom-gray-20 border-custom-gray-40 rounded focus:ring-primary focus:ring-2">
                    <span class="ml-2 text-sm text-custom-gray-90">Ingat Saya</span>
                </label>
                <a href="#" class="text-sm font-medium text-primary hover:text-secondary transition-colors">
                    Lupa Password?
                </a>
            </div>

            <!-- Login Button -->
            <button type="submit" wire:loading.attr="disabled"
                class="w-full bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3.5 rounded-xl hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="login">Masuk</span>
                <span wire:loading wire:target="login" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="text-center text-white/60 text-sm pb-4">
        <p>© 2024 Sajadadir. All rights reserved.</p>
    </div>
</div>
