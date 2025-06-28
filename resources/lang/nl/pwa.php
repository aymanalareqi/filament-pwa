<?php

return [
    // Installation prompts
    'install_title' => 'App Installeren',
    'install_description' => 'Krijg een betere ervaring met de geïnstalleerde app',
    'install_button' => 'Installeren',
    'dismiss_button' => 'Sluiten',

    // iOS installation
    'ios_install_title' => 'App Installeren op iOS',
    'ios_install_description' => 'Om deze app op iOS te installeren:',
    'ios_step_1' => 'Tik op de Deel-knop',
    'ios_step_2' => 'Selecteer "Voeg toe aan beginscherm"',
    'ios_step_3' => 'Tik op "Voeg toe" om te bevestigen',
    'got_it' => 'Begrepen',

    // Updates
    'update_available' => 'Update Beschikbaar',
    'update_description' => 'Een nieuwe versie van de app is beschikbaar',
    'update_now' => 'Nu Updaten',
    'update_later' => 'Later',

    // Offline page
    'offline_title' => 'Je bent Offline',
    'offline_subtitle' => 'Het lijkt erop dat je je internetverbinding bent kwijtgeraakt. Maak je geen zorgen, je kunt nog steeds toegang krijgen tot sommige functies van het beheerpaneel.',
    'offline_status' => 'Offline',
    'online_status' => 'Online',
    'offline_indicator' => 'Geen internetverbinding',

    // Features
    'available_features' => 'Beschikbare Functies',
    'feature_cached_pages' => 'Toegang tot gecachte pagina\'s en gegevens',
    'feature_offline_forms' => 'Formulieren invullen (synchroniseert wanneer online)',
    'feature_local_storage' => 'Lokaal opgeslagen informatie bekijken',
    'feature_auto_sync' => 'Automatische synchronisatie wanneer verbinding terugkeert',

    // Actions
    'retry_connection' => 'Opnieuw Proberen',
    'go_home' => 'Naar Home',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'Web app manifest niet gevonden',
        'service_worker_missing' => 'Service worker niet gevonden',
        'icons_missing' => 'Vereiste PWA iconen niet gevonden',
        'https_required' => 'HTTPS is vereist voor PWA in productie',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Filament PWA Plugin instellen...',
        'publishing_assets' => 'PWA assets publiceren...',
        'generating_icons' => 'PWA iconen genereren...',
        'validating' => 'PWA setup valideren...',
        'completed' => 'Filament PWA setup voltooid!',
        'assets_published' => 'Assets succesvol gepubliceerd!',
        'icons_generated' => 'Iconen succesvol gegenereerd!',
        'validation_passed' => 'Alle PWA assets zijn aanwezig!',
        'validation_failed' => 'PWA validatie mislukt:',
        'source_not_found' => 'Bronafbeelding niet gevonden',
        'provide_source' => 'Geef een bronafbeelding op met --source=path/to/image.svg of --source=path/to/image.png',
        'svg_detected' => 'SVG bron gedetecteerd - gebruik van hoogwaardige vectorconversie',
        'raster_detected' => 'Rasterafbeelding gedetecteerd - gebruik van beeldverwerking',
        'imagick_unavailable' => 'Imagick extensie niet beschikbaar. Terugvallen op GD met SVG conversie.',
        'intervention_unavailable' => 'Intervention Image niet geïnstalleerd. GD fallback gebruiken.',
        'gd_fallback' => 'GD fallback gebruiken voor beeldverwerking (beperkte kwaliteit)',
        'install_imagick' => 'Installeer de Imagick extensie of Intervention Image voor betere kwaliteit.',
    ],
];
