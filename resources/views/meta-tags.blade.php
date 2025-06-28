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

{{-- PWA Installation Styles with Tailwind CSS and RTL/LTR Support --}}
<style>
    /* PWA Installation Banner - Using Tailwind-compatible classes with RTL/LTR support */
    .pwa-install-banner {
        @apply fixed bottom-0 inset-x-0 text-white p-4 transform translate-y-full transition-transform duration-300 ease-in-out z-[9999] shadow-2xl;
        background: linear-gradient(135deg, {{ $config['theme_color'] }} 0%, {{ $config['theme_color'] }}dd 100%);
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    }

    .pwa-install-banner.show {
        @apply translate-y-0;
    }

    .pwa-install-content {
        @apply flex items-center justify-between max-w-6xl mx-auto gap-4;
        /* RTL/LTR support for content layout */
        direction: inherit;
    }

    .pwa-install-text {
        @apply flex-1;
        /* RTL/LTR text alignment */
        text-align: start;
    }

    .pwa-install-title {
        @apply font-bold mb-1 text-base;
    }

    .pwa-install-description {
        @apply text-sm opacity-90;
    }

    .pwa-install-actions {
        @apply flex gap-2 flex-shrink-0;
        /* RTL/LTR support for action buttons */
        direction: ltr;
    }

    .pwa-install-btn {
        @apply px-4 py-2 border-2 border-white bg-transparent text-white rounded-md cursor-pointer text-sm transition-all duration-200 no-underline inline-flex items-center gap-1;
    }

    .pwa-install-btn:hover {
        @apply bg-white/10 text-white no-underline;
    }

    .pwa-install-btn.primary {
        @apply bg-white;
        color: {{ $config['theme_color'] }};
    }

    .pwa-install-btn.primary:hover {
        @apply bg-white/90;
        color: {{ $config['theme_color'] }}dd;
    }

    .pwa-install-icon {
        @apply w-4 h-4;
    }

    /* Responsive design with RTL/LTR support */
    @media (max-width: 480px) {
        .pwa-install-content {
            @apply flex-col text-center;
        }

        .pwa-install-actions {
            @apply w-full justify-center;
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
            @apply !hidden;
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
        @apply flex-row-reverse;
    }

    [dir="rtl"] .pwa-install-actions {
        @apply flex-row-reverse;
    }

    /* LTR-specific adjustments (explicit for clarity) */
    [dir="ltr"] .pwa-install-content {
        @apply flex-row;
    }

    [dir="ltr"] .pwa-install-actions {
        @apply flex-row;
    }
</style>
