<?php

return [
    // Installation prompts
    'install_title' => 'Install App',
    'install_description' => 'Get a better experience with the installed app',
    'install_button' => 'Install',
    'dismiss_button' => 'Dismiss',

    // iOS installation
    'ios_install_title' => 'Install App on iOS',
    'ios_install_description' => 'To install this app on iOS:',
    'ios_step_1' => 'Tap the Share button',
    'ios_step_2' => 'Select "Add to Home Screen"',
    'ios_step_3' => 'Tap "Add" to confirm',
    'got_it' => 'Got it',

    // Updates
    'update_available' => 'Update Available',
    'update_description' => 'A new version of the app is available',
    'update_now' => 'Update Now',
    'update_later' => 'Later',

    // Offline page
    'offline_title' => 'You\'re Offline',
    'offline_subtitle' => 'It looks like you\'ve lost your internet connection. Don\'t worry, you can still access some features of the admin panel.',
    'offline_status' => 'Offline',
    'online_status' => 'Online',
    'offline_indicator' => 'No internet connection',

    // Features
    'available_features' => 'Available Features',
    'feature_cached_pages' => 'Access cached pages and data',
    'feature_offline_forms' => 'Fill out forms (will sync when online)',
    'feature_local_storage' => 'View locally stored information',
    'feature_auto_sync' => 'Automatic sync when connection returns',

    // Actions
    'retry_connection' => 'Try Again',
    'go_home' => 'Go Home',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'Web app manifest not found',
        'service_worker_missing' => 'Service worker not found',
        'icons_missing' => 'Required PWA icons not found',
        'https_required' => 'HTTPS is required for PWA in production',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Setting up Filament PWA Plugin...',
        'publishing_assets' => 'Publishing PWA assets...',
        'generating_icons' => 'Generating PWA icons...',
        'validating' => 'Validating PWA setup...',
        'completed' => 'Filament PWA setup completed!',
        'assets_published' => 'Assets published successfully!',
        'icons_generated' => 'Icons generated successfully!',
        'validation_passed' => 'All PWA assets are present!',
        'validation_failed' => 'PWA validation failed:',
        'source_not_found' => 'Source image not found',
        'provide_source' => 'Please provide a source image using --source=path/to/image.svg or --source=path/to/image.png',
        'svg_detected' => 'Detected SVG source - using high-quality vector conversion',
        'raster_detected' => 'Detected raster image - using image processing',
        'imagick_unavailable' => 'Imagick extension not available. Falling back to GD with SVG conversion.',
        'intervention_unavailable' => 'Intervention Image not installed. Using GD fallback.',
        'gd_fallback' => 'Using GD fallback for image processing (limited quality)',
        'install_imagick' => 'Please install Imagick extension or Intervention Image for better quality.',
    ],
];
