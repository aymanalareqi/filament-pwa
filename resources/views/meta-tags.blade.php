{{-- PWA Meta Tags for Filament Admin Panel --}}

{{-- Basic PWA Meta Tags --}}
<meta name="application-name" content="{{ $config['short_name'] }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ $config['short_name'] }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="msapplication-TileColor" content="{{ $config['theme_color'] }}">
<meta name="msapplication-tap-highlight" content="no">
<meta name="theme-color" content="{{ $config['theme_color'] }}">

{{-- Manifest Link --}}
<link rel="manifest" href="{{ route('filament-pwa.manifest') }}">

{{-- Apple Touch Icons --}}
<link rel="apple-touch-icon" href="{{ asset('images/icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/icon-192x192.png') }}">

{{-- Standard Favicon --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/icon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icons/icon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

{{-- Microsoft Tiles --}}
<meta name="msapplication-TileImage" content="{{ asset('images/icons/icon-144x144.png') }}">
<meta name="msapplication-config" content="{{ route('filament-pwa.browser-config') }}">

{{-- PWA Display Mode --}}
<meta name="display-mode" content="{{ $config['display'] }}">

{{-- Viewport for PWA --}}
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=no">

{{-- Security Headers for PWA --}}
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="format-detection" content="telephone=no">

{{-- PWA Splash Screens for iOS --}}
{{-- iPhone X/XS/11 Pro --}}
<link rel="apple-touch-startup-image" 
      media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" 
      href="{{ asset('images/splash/iphone-x.png') }}">

{{-- iPhone XR/11 --}}
<link rel="apple-touch-startup-image" 
      media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" 
      href="{{ asset('images/splash/iphone-xr.png') }}">

{{-- iPhone XS Max/11 Pro Max --}}
<link rel="apple-touch-startup-image" 
      media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" 
      href="{{ asset('images/splash/iphone-xs-max.png') }}">

{{-- iPad --}}
<link rel="apple-touch-startup-image" 
      media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" 
      href="{{ asset('images/splash/ipad.png') }}">

{{-- iPad Pro 11" --}}
<link rel="apple-touch-startup-image" 
      media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" 
      href="{{ asset('images/splash/ipad-pro-11.png') }}">

{{-- iPad Pro 12.9" --}}
<link rel="apple-touch-startup-image" 
      media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" 
      href="{{ asset('images/splash/ipad-pro-12.png') }}">

{{-- PWA Installation Styles --}}
<style>
    /* PWA Installation Banner Styles */
    .pwa-install-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, {{ $config['theme_color'] }} 0%, {{ $config['theme_color'] }}dd 100%);
        color: white;
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 9999;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    }

    .pwa-install-banner.show {
        transform: translateY(0);
    }

    .pwa-install-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
        gap: 1rem;
    }

    .pwa-install-text {
        flex: 1;
    }

    .pwa-install-title {
        font-weight: bold;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .pwa-install-description {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .pwa-install-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .pwa-install-btn {
        padding: 0.5rem 1rem;
        border: 2px solid white;
        background: transparent;
        color: white;
        border-radius: 0.375rem;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .pwa-install-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        text-decoration: none;
    }

    .pwa-install-btn.primary {
        background: white;
        color: {{ $config['theme_color'] }};
    }

    .pwa-install-btn.primary:hover {
        background: rgba(255, 255, 255, 0.9);
        color: {{ $config['theme_color'] }}dd;
    }

    .pwa-install-icon {
        width: 1rem;
        height: 1rem;
    }

    /* Hide banner on very small screens */
    @media (max-width: 480px) {
        .pwa-install-content {
            flex-direction: column;
            text-align: center;
        }
        
        .pwa-install-actions {
            width: 100%;
            justify-content: center;
        }
    }

    /* PWA Standalone Mode Styles */
    @media (display-mode: standalone) {
        body {
            /* Add padding for status bar in standalone mode */
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
        }
        
        /* Hide PWA install banner when already installed */
        .pwa-install-banner {
            display: none !important;
        }
    }

    /* iOS Safari specific styles */
    @supports (-webkit-touch-callout: none) {
        .pwa-install-banner {
            padding-bottom: calc(1rem + env(safe-area-inset-bottom));
        }
    }
</style>
