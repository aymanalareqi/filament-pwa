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

{{-- PWA Installation Styles with Tailwind CSS v4 compatibility and RTL/LTR Support --}}
<style>
    /* PWA Installation Banner - Compatible with Tailwind CSS v4 */
    .pwa-install-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        color: white;
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 9999;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15), 0 -10px 40px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, {{ $config['theme_color'] }} 0%, {{ $config['theme_color'] }}dd 100%);
        backdrop-filter: blur(10px);
    }

    .pwa-install-banner.show {
        transform: translateY(0);
    }

    .pwa-install-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 72rem;
        margin: 0 auto;
        gap: 1rem;
        direction: inherit;
    }

    .pwa-install-text {
        flex: 1;
        text-align: start;
    }

    .pwa-install-title {
        font-weight: bold;
        margin-bottom: 0.25rem;
        font-size: 1rem;
        line-height: 1.5;
    }

    .pwa-install-description {
        font-size: 0.875rem;
        line-height: 1.25rem;
        opacity: 0.9;
    }

    .pwa-install-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
        direction: ltr;
    }

    .pwa-install-btn {
        padding: 0.5rem 1rem;
        border: 2px solid white;
        background: transparent;
        color: white;
        border-radius: 0.375rem;
        cursor: pointer;
        font-size: 0.875rem;
        line-height: 1.25rem;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-weight: 500;
    }

    .pwa-install-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .pwa-install-btn.primary {
        background: white;
        color: {{ $config['theme_color'] }};
        border-color: white;
    }

    .pwa-install-btn.primary:hover {
        background: rgba(255, 255, 255, 0.9);
        color: {{ $config['theme_color'] }};
        transform: translateY(-1px);
    }

    .pwa-install-icon {
        width: 1rem;
        height: 1rem;
        flex-shrink: 0;
    }

    /* Responsive design with RTL/LTR support */
    @media (max-width: 480px) {
        .pwa-install-content {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }

        .pwa-install-actions {
            width: 100%;
            justify-content: center;
        }

        .pwa-install-btn {
            flex: 1;
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

    /* iOS Safari specific styles with RTL/LTR support */
    @supports (-webkit-touch-callout: none) {
        .pwa-install-banner {
            padding-bottom: calc(1rem + env(safe-area-inset-bottom));
        }
    }

    /* RTL-specific adjustments */
    [dir="rtl"] .pwa-install-content {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .pwa-install-actions {
        flex-direction: row-reverse;
    }

    /* LTR-specific adjustments (explicit for clarity) */
    [dir="ltr"] .pwa-install-content {
        flex-direction: row;
    }

    [dir="ltr"] .pwa-install-actions {
        flex-direction: row;
    }

    /* Enhanced visual improvements for better UX */
    .pwa-install-banner {
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .pwa-install-btn:focus {
        outline: 2px solid rgba(255, 255, 255, 0.5);
        outline-offset: 2px;
    }

    .pwa-install-btn:active {
        transform: translateY(0);
    }

    /* Dark mode compatibility */
    @media (prefers-color-scheme: dark) {
        .pwa-install-banner {
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3), 0 -10px 40px rgba(0, 0, 0, 0.2);
        }
    }

    /* Filament v4 compatibility - ensure proper z-index stacking */
    .pwa-install-banner {
        z-index: 99999;
    }

    /* Ensure banner works with Filament's layout */
    .fi-layout .pwa-install-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
    }

    /* Animation improvements for smoother transitions */
    .pwa-install-banner {
        will-change: transform;
    }

    .pwa-install-banner.show {
        animation: slideUp 0.3s ease-out forwards;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Ensure proper text rendering */
    .pwa-install-banner * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>
