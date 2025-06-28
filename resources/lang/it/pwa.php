<?php

return [
    // Installation prompts
    'install_title' => 'Installa App',
    'install_description' => 'Ottieni un\'esperienza migliore con l\'app installata',
    'install_button' => 'Installa',
    'dismiss_button' => 'Ignora',

    // iOS installation
    'ios_install_title' => 'Installa App su iOS',
    'ios_install_description' => 'Per installare questa app su iOS:',
    'ios_step_1' => 'Tocca il pulsante Condividi',
    'ios_step_2' => 'Seleziona "Aggiungi alla schermata Home"',
    'ios_step_3' => 'Tocca "Aggiungi" per confermare',
    'got_it' => 'Capito',

    // Updates
    'update_available' => 'Aggiornamento Disponibile',
    'update_description' => 'È disponibile una nuova versione dell\'app',
    'update_now' => 'Aggiorna Ora',
    'update_later' => 'Più Tardi',

    // Offline page
    'offline_title' => 'Sei Offline',
    'offline_subtitle' => 'Sembra che tu abbia perso la connessione internet. Non preoccuparti, puoi ancora accedere ad alcune funzionalità del pannello di amministrazione.',
    'offline_status' => 'Offline',
    'online_status' => 'Online',
    'offline_indicator' => 'Nessuna connessione internet',

    // Features
    'available_features' => 'Funzionalità Disponibili',
    'feature_cached_pages' => 'Accedi a pagine e dati memorizzati nella cache',
    'feature_offline_forms' => 'Compila moduli (si sincronizzeranno quando online)',
    'feature_local_storage' => 'Visualizza informazioni memorizzate localmente',
    'feature_auto_sync' => 'Sincronizzazione automatica al ritorno della connessione',

    // Actions
    'retry_connection' => 'Riprova',
    'go_home' => 'Vai alla Home',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'Manifesto dell\'app web non trovato',
        'service_worker_missing' => 'Service worker non trovato',
        'icons_missing' => 'Icone PWA richieste non trovate',
        'https_required' => 'HTTPS è richiesto per PWA in produzione',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Configurazione Plugin Filament PWA...',
        'publishing_assets' => 'Pubblicazione risorse PWA...',
        'generating_icons' => 'Generazione icone PWA...',
        'validating' => 'Validazione configurazione PWA...',
        'completed' => 'Configurazione Filament PWA completata!',
        'assets_published' => 'Risorse pubblicate con successo!',
        'icons_generated' => 'Icone generate con successo!',
        'validation_passed' => 'Tutte le risorse PWA sono presenti!',
        'validation_failed' => 'Validazione PWA fallita:',
        'source_not_found' => 'Immagine sorgente non trovata',
        'provide_source' => 'Si prega di fornire un\'immagine sorgente usando --source=path/to/image.svg o --source=path/to/image.png',
        'svg_detected' => 'Sorgente SVG rilevata - usando conversione vettoriale di alta qualità',
        'raster_detected' => 'Immagine raster rilevata - usando elaborazione immagini',
        'imagick_unavailable' => 'Estensione Imagick non disponibile. Ricorrendo a GD con conversione SVG.',
        'intervention_unavailable' => 'Intervention Image non installato. Usando fallback GD.',
        'gd_fallback' => 'Usando fallback GD per elaborazione immagini (qualità limitata)',
        'install_imagick' => 'Si prega di installare l\'estensione Imagick o Intervention Image per una qualità migliore.',
    ],
];
