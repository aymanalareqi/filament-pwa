<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PWA Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Filament PWA plugin.
    | You can customize various aspects of your Progressive Web App here.
    |
    | Tip: You can also configure these options using the FilamentPwaPlugin
    | fluent methods in your panel provider for a more programmatic approach.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | App Information
    |--------------------------------------------------------------------------
    |
    | Configure the basic information about your PWA application.
    |
    */

    'name' => env('PWA_APP_NAME', config('app.name', 'Laravel') . ' Admin'),
    'short_name' => env('PWA_SHORT_NAME', 'Admin'),
    'description' => env('PWA_DESCRIPTION', 'Admin panel for ' . config('app.name', 'Laravel')),

    /*
    |--------------------------------------------------------------------------
    | PWA Display Settings
    |--------------------------------------------------------------------------
    |
    | Configure how your PWA should be displayed when installed.
    |
    | Available display modes: standalone, fullscreen, minimal-ui, browser
    | Available orientations: portrait, landscape, portrait-primary, landscape-primary, any
    |
    */

    'start_url' => env('PWA_START_URL', '/admin'),
    'display' => env('PWA_DISPLAY', 'standalone'),
    'orientation' => env('PWA_ORIENTATION', 'portrait-primary'),
    'scope' => env('PWA_SCOPE', '/admin'),

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the visual theme of your PWA.
    | By default, these values attempt to inherit from Filament's design system.
    |
    */

    'theme_color' => env('PWA_THEME_COLOR', null), // Will auto-detect from Filament if null

    'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Configure the language and text direction for your PWA.
    | By default, these values inherit from Laravel's app configuration.
    | Supported directions: ltr, rtl
    |
    */

    'lang' => env('PWA_LANG', null), // Will auto-detect from Laravel app locale if null
    'dir' => env('PWA_DIR', null), // Will auto-detect from language if null

    /*
    |--------------------------------------------------------------------------
    | PWA Categories
    |--------------------------------------------------------------------------
    |
    | Define the categories that best describe your PWA.
    | Common categories: productivity, business, utilities, lifestyle, social
    |
    */

    'categories' => [
        'productivity',
        'business',
        'utilities',
    ],

    /*
    |--------------------------------------------------------------------------
    | Installation & User Experience
    |--------------------------------------------------------------------------
    |
    | Configure the PWA installation prompts and user experience settings.
    |
    */

    'installation' => [
        'enabled' => env('PWA_INSTALLATION_ENABLED', true),
        'prompt_delay' => env('PWA_INSTALLATION_DELAY', 2000), // milliseconds
        'ios_instructions_delay' => env('PWA_IOS_INSTRUCTIONS_DELAY', 5000), // milliseconds

        // Debug-only option: Always show installation banner in debug mode
        // This bypasses dismissal logic and browser installation state checks
        // Useful for testing PWA installation flow during development
        'show_banner_in_debug' => env('PWA_SHOW_BANNER_IN_DEBUG', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the icons for your PWA. Icons will be automatically generated
    | from your source file using the setup command.
    |
    */

    'icons' => [
        'path' => env('PWA_ICONS_PATH', 'images/icons'),
        'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
        'maskable_sizes' => [192, 512],
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Worker & Caching
    |--------------------------------------------------------------------------
    |
    | Configure the service worker behavior and caching strategies.
    |
    */

    'service_worker' => [
        'cache_name' => env('PWA_CACHE_NAME', 'filament-admin-v1.0.0'),
        'offline_url' => env('PWA_OFFLINE_URL', '/offline'),
        'cache_urls' => [
            '/admin',
            '/admin/login',
            '/manifest.json',
        ],
        'cache_patterns' => [
            'filament_assets' => '/\/css\/filament\/|\/js\/filament\//',
            'images' => '/\.(png|jpg|jpeg|svg|gif|webp|ico)$/',
            'fonts' => '/\.(woff|woff2|ttf|eot)$/',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | App Shortcuts
    |--------------------------------------------------------------------------
    |
    | Define shortcuts that will appear in the PWA app menu when users
    | long-press the app icon on their device.
    |
    */

    'shortcuts' => [
        [
            'name' => 'Dashboard',
            'short_name' => 'Dashboard',
            'description' => 'Go to the main dashboard',
            'url' => '/admin',
            'icons' => [
                [
                    'src' => '/images/icons/icon-96x96.png',
                    'sizes' => '96x96',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Options
    |--------------------------------------------------------------------------
    |
    | Additional PWA configuration options for advanced use cases.
    |
    */

    // Set to true if you have a native app and want to promote it over the PWA
    'prefer_related_applications' => env('PWA_PREFER_NATIVE_APP', false),

    // Screenshots for enhanced installation prompts (optional)
    'screenshots' => [
        // Example:
        // [
        //     'src' => '/images/screenshots/desktop.png',
        //     'sizes' => '1280x720',
        //     'type' => 'image/png',
        //     'form_factor' => 'wide',
        // ],
    ],

    // Related native applications (optional)
    'related_applications' => [
        // Example:
        // [
        //     'platform' => 'play',
        //     'url' => 'https://play.google.com/store/apps/details?id=com.example.app',
        //     'id' => 'com.example.app',
        // ],
    ],
];
