<?php

return [
    // Installation prompts
    'install_title' => 'Installer l\'Application',
    'install_description' => 'Obtenez une meilleure expérience avec l\'application installée',
    'install_button' => 'Installer',
    'dismiss_button' => 'Ignorer',

    // iOS installation
    'ios_install_title' => 'Installer l\'Application sur iOS',
    'ios_install_description' => 'Pour installer cette application sur iOS :',
    'ios_step_1' => 'Appuyez sur le bouton Partager',
    'ios_step_2' => 'Sélectionnez "Ajouter à l\'écran d\'accueil"',
    'ios_step_3' => 'Appuyez sur "Ajouter" pour confirmer',
    'got_it' => 'Compris',

    // Updates
    'update_available' => 'Mise à Jour Disponible',
    'update_description' => 'Une nouvelle version de l\'application est disponible',
    'update_now' => 'Mettre à Jour Maintenant',
    'update_later' => 'Plus Tard',

    // Offline page
    'offline_title' => 'Vous êtes Hors Ligne',
    'offline_subtitle' => 'Il semble que vous ayez perdu votre connexion internet. Ne vous inquiétez pas, vous pouvez toujours accéder à certaines fonctionnalités du panneau d\'administration.',
    'offline_status' => 'Hors ligne',
    'online_status' => 'En ligne',
    'offline_indicator' => 'Aucune connexion internet',

    // Features
    'available_features' => 'Fonctionnalités Disponibles',
    'feature_cached_pages' => 'Accéder aux pages et données en cache',
    'feature_offline_forms' => 'Remplir des formulaires (synchronisation lors de la reconnexion)',
    'feature_local_storage' => 'Voir les informations stockées localement',
    'feature_auto_sync' => 'Synchronisation automatique au retour de la connexion',

    // Actions
    'retry_connection' => 'Réessayer',
    'go_home' => 'Aller à l\'Accueil',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'Manifeste d\'application web introuvable',
        'service_worker_missing' => 'Service worker introuvable',
        'icons_missing' => 'Icônes PWA requises introuvables',
        'https_required' => 'HTTPS est requis pour PWA en production',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Configuration du Plugin Filament PWA...',
        'publishing_assets' => 'Publication des ressources PWA...',
        'generating_icons' => 'Génération des icônes PWA...',
        'validating' => 'Validation de la configuration PWA...',
        'completed' => 'Configuration Filament PWA terminée !',
        'assets_published' => 'Ressources publiées avec succès !',
        'icons_generated' => 'Icônes générées avec succès !',
        'validation_passed' => 'Toutes les ressources PWA sont présentes !',
        'validation_failed' => 'Validation PWA échouée :',
        'source_not_found' => 'Image source introuvable',
        'provide_source' => 'Veuillez fournir une image source en utilisant --source=path/to/image.svg ou --source=path/to/image.png',
        'svg_detected' => 'Source SVG détectée - utilisation de la conversion vectorielle haute qualité',
        'raster_detected' => 'Image raster détectée - utilisation du traitement d\'images',
        'imagick_unavailable' => 'Extension Imagick non disponible. Recours à GD avec conversion SVG.',
        'intervention_unavailable' => 'Intervention Image non installé. Utilisation du fallback GD.',
        'gd_fallback' => 'Utilisation du fallback GD pour le traitement d\'images (qualité limitée)',
        'install_imagick' => 'Veuillez installer l\'extension Imagick ou Intervention Image pour une meilleure qualité.',
    ],
];
