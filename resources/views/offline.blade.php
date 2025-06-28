<!DOCTYPE html>
<html lang="{{ $config['lang'] }}" dir="{{ $config['dir'] }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('filament-pwa::pwa.offline_title') }} - {{ $config['name'] }}</title>
    <meta name="theme-color" content="{{ $config['theme_color'] }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/icon-32x32.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, {{ $config['background_color'] }} 0%, #f8fafc 100%);
            color: #374151;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            direction: {{ $config['dir'] }};
        }

        .offline-container {
            max-width: 500px;
            width: 100%;
            background: white;
            padding: 3rem 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            text-align: center;
            position: relative;
            overflow: hidden;
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

        .offline-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.8;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .features {
            background: #f9fafb;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: {{ $config['dir'] === 'rtl' ? 'right' : 'left' }};
        }

        .features h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #374151;
        }

        .features ul {
            list-style: none;
            padding: 0;
        }

        .features li {
            padding: 0.5rem 0;
            color: #6b7280;
            position: relative;
            padding-{{ $config['dir'] === 'rtl' ? 'right' : 'left' }}: 1.5rem;
        }

        .features li::before {
            content: '✓';
            position: absolute;
            {{ $config['dir'] === 'rtl' ? 'right' : 'left' }}: 0;
            color: {{ $config['theme_color'] }};
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: {{ $config['theme_color'] }};
            color: white;
        }

        .btn-primary:hover {
            background: {{ $config['theme_color'] }}dd;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        .status-indicator {
            position: fixed;
            top: 1rem;
            {{ $config['dir'] === 'rtl' ? 'left' : 'right' }}: 1rem;
            background: #ef4444;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-indicator.online {
            background: #10b981;
        }

        .pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @media (max-width: 640px) {
            .offline-container {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* RTL specific styles */
        @if($config['dir'] === 'rtl')
        body {
            font-family: 'Tajawal', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        @endif
    </style>
</head>
<body>
    <div class="status-indicator" id="status-indicator">
        <div class="pulse"></div>
        <span id="status-text">{{ __('filament-pwa::pwa.offline_status') }}</span>
    </div>

    <div class="offline-container">
        <div class="offline-icon">📱</div>
        
        <h1>{{ __('filament-pwa::pwa.offline_title') }}</h1>
        
        <p class="subtitle">
            {{ __('filament-pwa::pwa.offline_subtitle') }}
        </p>

        <div class="features">
            <h3>{{ __('filament-pwa::pwa.available_features') }}</h3>
            <ul>
                <li>{{ __('filament-pwa::pwa.feature_cached_pages') }}</li>
                <li>{{ __('filament-pwa::pwa.feature_offline_forms') }}</li>
                <li>{{ __('filament-pwa::pwa.feature_local_storage') }}</li>
                <li>{{ __('filament-pwa::pwa.feature_auto_sync') }}</li>
            </ul>
        </div>

        <div class="actions">
            <button class="btn btn-primary" onclick="window.location.reload()">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                </svg>
                {{ __('filament-pwa::pwa.retry_connection') }}
            </button>
            
            <a href="{{ $config['start_url'] }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
                </svg>
                {{ __('filament-pwa::pwa.go_home') }}
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
