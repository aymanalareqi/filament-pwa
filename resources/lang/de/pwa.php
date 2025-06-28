<?php

return [
    // Installation prompts
    'install_title' => 'App Installieren',
    'install_description' => 'Erhalten Sie eine bessere Erfahrung mit der installierten App',
    'install_button' => 'Installieren',
    'dismiss_button' => 'Verwerfen',

    // iOS installation
    'ios_install_title' => 'App auf iOS Installieren',
    'ios_install_description' => 'Um diese App auf iOS zu installieren:',
    'ios_step_1' => 'Tippen Sie auf die Teilen-Schaltfläche',
    'ios_step_2' => 'Wählen Sie "Zum Home-Bildschirm hinzufügen"',
    'ios_step_3' => 'Tippen Sie auf "Hinzufügen" zum Bestätigen',
    'got_it' => 'Verstanden',

    // Updates
    'update_available' => 'Update Verfügbar',
    'update_description' => 'Eine neue Version der App ist verfügbar',
    'update_now' => 'Jetzt Aktualisieren',
    'update_later' => 'Später',

    // Offline page
    'offline_title' => 'Sie sind Offline',
    'offline_subtitle' => 'Es scheint, als hätten Sie Ihre Internetverbindung verloren. Keine Sorge, Sie können immer noch auf einige Funktionen des Admin-Panels zugreifen.',
    'offline_status' => 'Offline',
    'online_status' => 'Online',
    'offline_indicator' => 'Keine Internetverbindung',

    // Features
    'available_features' => 'Verfügbare Funktionen',
    'feature_cached_pages' => 'Zugriff auf zwischengespeicherte Seiten und Daten',
    'feature_offline_forms' => 'Formulare ausfüllen (werden bei Online-Verbindung synchronisiert)',
    'feature_local_storage' => 'Lokal gespeicherte Informationen anzeigen',
    'feature_auto_sync' => 'Automatische Synchronisation bei Verbindungsrückkehr',

    // Actions
    'retry_connection' => 'Erneut Versuchen',
    'go_home' => 'Zur Startseite',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'Web-App-Manifest nicht gefunden',
        'service_worker_missing' => 'Service Worker nicht gefunden',
        'icons_missing' => 'Erforderliche PWA-Icons nicht gefunden',
        'https_required' => 'HTTPS ist für PWA in der Produktion erforderlich',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Filament PWA Plugin wird eingerichtet...',
        'publishing_assets' => 'PWA-Assets werden veröffentlicht...',
        'generating_icons' => 'PWA-Icons werden generiert...',
        'validating' => 'PWA-Setup wird validiert...',
        'completed' => 'Filament PWA Setup abgeschlossen!',
        'assets_published' => 'Assets erfolgreich veröffentlicht!',
        'icons_generated' => 'Icons erfolgreich generiert!',
        'validation_passed' => 'Alle PWA-Assets sind vorhanden!',
        'validation_failed' => 'PWA-Validierung fehlgeschlagen:',
        'source_not_found' => 'Quellbild nicht gefunden',
        'provide_source' => 'Bitte geben Sie ein Quellbild mit --source=path/to/image.svg oder --source=path/to/image.png an',
        'svg_detected' => 'SVG-Quelle erkannt - verwende hochwertige Vektorkonvertierung',
        'raster_detected' => 'Rasterbild erkannt - verwende Bildverarbeitung',
        'imagick_unavailable' => 'Imagick-Erweiterung nicht verfügbar. Fallback auf GD mit SVG-Konvertierung.',
        'intervention_unavailable' => 'Intervention Image nicht installiert. Verwende GD-Fallback.',
        'gd_fallback' => 'Verwende GD-Fallback für Bildverarbeitung (begrenzte Qualität)',
        'install_imagick' => 'Bitte installieren Sie die Imagick-Erweiterung oder Intervention Image für bessere Qualität.',
    ],
];
