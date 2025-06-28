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
    */

    /*
    |--------------------------------------------------------------------------
    | App Information
    |--------------------------------------------------------------------------
    |
    | Configure the basic information about your PWA application.
    |
    */

    'app_name' => env('PWA_APP_NAME', config('app.name', 'Laravel') . ' Admin'),
    'short_name' => env('PWA_SHORT_NAME', 'Admin'),
    'description' => env('PWA_DESCRIPTION', 'Admin panel for ' . config('app.name', 'Laravel')),

    /*
    |--------------------------------------------------------------------------
    | PWA Display Settings
    |--------------------------------------------------------------------------
    |
    | Configure how your PWA should be displayed when installed.
    |
    */

    'start_url' => env('PWA_START_URL', '/admin'),
    'display' => env('PWA_DISPLAY', 'standalone'), // standalone, fullscreen, minimal-ui, browser
    'orientation' => env('PWA_ORIENTATION', 'portrait-primary'), // portrait, landscape, any
    'scope' => env('PWA_SCOPE', '/admin'),

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the visual theme of your PWA.
    |
    */

    'theme_color' => env('PWA_THEME_COLOR', '#A77B56'),
    'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Configure the language and text direction for your PWA.
    |
    */

    'lang' => env('PWA_LANG', 'en'),
    'dir' => env('PWA_DIR', 'ltr'), // ltr, rtl

    /*
    |--------------------------------------------------------------------------
    | PWA Categories
    |--------------------------------------------------------------------------
    |
    | Define the categories that best describe your PWA.
    |
    */

    'categories' => [
        'productivity',
        'business',
        'utilities',
    ],

    /*
    |--------------------------------------------------------------------------
    | Installation Prompts
    |--------------------------------------------------------------------------
    |
    | Configure the PWA installation prompts and banners.
    |
    */

    'installation_prompts' => [
        'enabled' => env('PWA_INSTALLATION_PROMPTS', true),
        'delay' => env('PWA_INSTALLATION_DELAY', 2000), // milliseconds
        'ios_instructions_delay' => env('PWA_IOS_INSTRUCTIONS_DELAY', 5000), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the icons for your PWA.
    |
    */

    'icons' => [
        'source_path' => env('PWA_ICON_SOURCE', 'icon.svg'),
        'output_path' => 'images/icons',
        'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
        'maskable_sizes' => [192, 512],
        'additional_sizes' => [16, 32, 70, 150, 310], // For favicons and tiles
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Worker Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the service worker behavior.
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
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | Define the middleware that should be applied to PWA routes.
    |
    */

    'route_middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Shortcuts
    |--------------------------------------------------------------------------
    |
    | Define shortcuts that will appear in the PWA app menu.
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
    | Screenshots
    |--------------------------------------------------------------------------
    |
    | Define screenshots for enhanced installation prompts.
    |
    */

    'screenshots' => [
        // Add screenshots for better installation experience
        // [
        //     'src' => '/images/screenshots/desktop.png',
        //     'sizes' => '1280x720',
        //     'type' => 'image/png',
        //     'form_factor' => 'wide',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Related Applications
    |--------------------------------------------------------------------------
    |
    | Configure related native applications.
    |
    */

    'prefer_related_applications' => false,
    'related_applications' => [
        // [
        //     'platform' => 'play',
        //     'url' => 'https://play.google.com/store/apps/details?id=com.example.app',
        //     'id' => 'com.example.app',
        // ],
    ],
];
