<!DOCTYPE html>
<html lang="{{ $config['lang'] }}" dir="{{ $config['dir'] }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('filament-pwa::pwa.offline_title') }} - {{ $config['name'] }}</title>
    <meta name="theme-color" content="{{ $config['theme_color'] }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/icon-32x32.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $config["theme_color"] }}',
                        background: '{{ $config["background_color"] }}'
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles for elements that need dynamic colors or complex gradients */
        body {
            background: linear-gradient(135deg, {{ $config['background_color'] }} 0%, #f8fafc 100%);
        }

        .offline-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, {{ $config['theme_color'] }}, {{ $config['theme_color'] }}dd);
        }

        .btn-primary {
            background-color: {{ $config['theme_color'] }};
        }

        .btn-primary:hover {
            background-color: {{ $config['theme_color'] }}dd;
        }

        .status-indicator {
            background-color: #ef4444;
        }

        .status-indicator.online {
            background-color: #10b981;
        }

        .features li::before {
            color: {{ $config['theme_color'] }};
        }

        /* RTL specific font family */
        @if($config['dir'] === 'rtl')
        body {
            font-family: 'Tajawal', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        @endif

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .pulse {
            animation: pulse 2s infinite;
        }


    </style>
</head>
<body class="font-sans text-gray-700 min-h-screen flex items-center justify-center p-4" style="direction: {{ $config['dir'] }};">
    <!-- Status Indicator with RTL/LTR support -->
    <div class="status-indicator fixed top-4 {{ $config['dir'] === 'rtl' ? 'start-4' : 'end-4' }} text-white px-4 py-2 rounded-lg text-sm font-medium z-50 flex items-center gap-2" id="status-indicator">
        <div class="pulse w-2 h-2 rounded-full bg-current"></div>
        <span id="status-text">{{ __('filament-pwa::pwa.offline_status') }}</span>
    </div>

    <!-- Main Container -->
    <div class="offline-container max-w-lg w-full bg-white p-12 sm:p-6 rounded-3xl shadow-2xl text-center relative overflow-hidden">
        <!-- Top accent bar -->
        <div class="absolute top-0 inset-x-0 h-1"></div>

        <!-- Offline Icon -->
        <div class="text-8xl mb-6 opacity-80">📱</div>

        <!-- Title -->
        <h1 class="text-3xl sm:text-2xl font-bold mb-4 text-gray-800">{{ __('filament-pwa::pwa.offline_title') }}</h1>

        <!-- Subtitle -->
        <p class="text-lg text-gray-500 mb-8 leading-relaxed">
            {{ __('filament-pwa::pwa.offline_subtitle') }}
        </p>

        <!-- Features Section with RTL/LTR support -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-8 text-start">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('filament-pwa::pwa.available_features') }}</h3>
            <ul class="list-none p-0 space-y-2">
                <li class="text-gray-500 relative ps-6">
                    <span class="absolute start-0 font-bold">✓</span>
                    {{ __('filament-pwa::pwa.feature_cached_pages') }}
                </li>
                <li class="text-gray-500 relative ps-6">
                    <span class="absolute start-0 font-bold">✓</span>
                    {{ __('filament-pwa::pwa.feature_offline_forms') }}
                </li>
                <li class="text-gray-500 relative ps-6">
                    <span class="absolute start-0 font-bold">✓</span>
                    {{ __('filament-pwa::pwa.feature_local_storage') }}
                </li>
                <li class="text-gray-500 relative ps-6">
                    <span class="absolute start-0 font-bold">✓</span>
                    {{ __('filament-pwa::pwa.feature_auto_sync') }}
                </li>
            </ul>
        </div>

        <!-- Action Buttons with RTL/LTR support -->
        <div class="flex gap-4 justify-center flex-wrap sm:flex-col">
            <button class="btn-primary text-white px-6 py-3.5 rounded-xl text-base font-semibold no-underline transition-all duration-200 cursor-pointer border-none inline-flex items-center gap-2 hover:-translate-y-0.5 hover:shadow-lg sm:w-full sm:justify-center" onclick="window.location.reload()">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="{{ $config['dir'] === 'rtl' ? 'order-2' : '' }}">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                </svg>
                <span class="{{ $config['dir'] === 'rtl' ? 'order-1' : '' }}">{{ __('filament-pwa::pwa.retry_connection') }}</span>
            </button>

            <a href="{{ $config['start_url'] }}" class="bg-gray-100 text-gray-700 border border-gray-300 px-6 py-3.5 rounded-xl text-base font-semibold no-underline transition-all duration-200 cursor-pointer inline-flex items-center gap-2 hover:bg-gray-200 hover:-translate-y-0.5 sm:w-full sm:justify-center">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="{{ $config['dir'] === 'rtl' ? 'order-2' : '' }}">
                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
                </svg>
                <span class="{{ $config['dir'] === 'rtl' ? 'order-1' : '' }}">{{ __('filament-pwa::pwa.go_home') }}</span>
            </a>
        </div>
    </div>

    <script>
        // Monitor connection status
        function updateConnectionStatus() {
            const indicator = document.getElementById('status-indicator');
            const statusText = document.getElementById('status-text');
            
            if (navigator.onLine) {
                indicator.classList.add('online');
                statusText.textContent = '{{ __('filament-pwa::pwa.online_status') }}';
                
                // Auto-reload after 2 seconds when back online
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                indicator.classList.remove('online');
                statusText.textContent = '{{ __('filament-pwa::pwa.offline_status') }}';
            }
        }

        // Listen for connection changes
        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);

        // Initial status check
        updateConnectionStatus();

        // Periodic connection check
        setInterval(() => {
            // Try to fetch a small resource to verify connection
            fetch('/manifest.json', { 
                method: 'HEAD',
                cache: 'no-cache'
            }).then(() => {
                if (!navigator.onLine) {
                    // Force online status if fetch succeeds
                    window.dispatchEvent(new Event('online'));
                }
            }).catch(() => {
                if (navigator.onLine) {
                    // Force offline status if fetch fails
                    window.dispatchEvent(new Event('offline'));
                }
            });
        }, 5000);
    </script>
</body>
</html>
