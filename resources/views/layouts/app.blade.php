<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Presensi Karyawan' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Face API JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-custom-gray-20 font-sans antialiased">
    <!-- Global Toast Container -->
    <div id="toast-container" class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] w-full max-w-md px-4"></div>

    <div class="max-w-md mx-auto min-h-screen bg-white shadow-lg">
        {{ $slot }}
    </div>

    @livewireScripts

    <!-- Global Toast Script -->
    <script>
        /**
         * Global Toast Notification System
         * Usage: 
         *   showToast('Message', 'success') - Green toast
         *   showToast('Message', 'error')   - Red toast
         *   showToast('Message', 'info')    - Blue toast
         *   showToast('Message', 'warning') - Yellow toast
         * 
         * Shorthand:
         *   toastSuccess('Message')
         *   toastError('Message')
         *   toastInfo('Message')
         *   toastWarning('Message')
         */
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) {
                console.error('Toast container not found!');
                return;
            }

            // Color mapping
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500',
                warning: 'bg-yellow-500'
            };

            // Icon SVG paths
            const icons = {
                success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>'
            };

            const bgColor = colors[type] || colors.success;
            const icon = icons[type] || icons.success;

            // Create toast element
            const toast = document.createElement('div');
            toast.className =
                `${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 mb-2 opacity-0 transform translate-y-2 transition-all duration-300 ease-out`;

            toast.innerHTML = `
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${icon}
                </svg>
                <p class="font-semibold flex-1">${message}</p>
            `;

            // Add to container
            container.appendChild(toast);

            // Trigger animation (double RAF for smooth animation)
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    toast.classList.remove('opacity-0', 'translate-y-2');
                    toast.classList.add('opacity-100', 'translate-y-0');
                });
            });

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', '-translate-y-2');

                setTimeout(() => {
                    if (container.contains(toast)) {
                        container.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        };

        // Shorthand helper functions
        window.toastSuccess = (msg) => showToast(msg, 'success');
        window.toastError = (msg) => showToast(msg, 'error');
        window.toastInfo = (msg) => showToast(msg, 'info');
        window.toastWarning = (msg) => showToast(msg, 'warning');

        // Handle flash session from backend (optional)
        @if (session()->has('toast'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast(
                    @json(session('toast.message')),
                    @json(session('toast.type') ?? 'success')
                );
            });
        @endif
    </script>
</body>

</html>
