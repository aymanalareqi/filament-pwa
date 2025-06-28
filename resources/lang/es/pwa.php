<?php

return [
    // Installation prompts
    'install_title' => 'Instalar Aplicación',
    'install_description' => 'Obtén una mejor experiencia con la aplicación instalada',
    'install_button' => 'Instalar',
    'dismiss_button' => 'Descartar',

    // iOS installation
    'ios_install_title' => 'Instalar Aplicación en iOS',
    'ios_install_description' => 'Para instalar esta aplicación en iOS:',
    'ios_step_1' => 'Toca el botón Compartir',
    'ios_step_2' => 'Selecciona "Añadir a pantalla de inicio"',
    'ios_step_3' => 'Toca "Añadir" para confirmar',
    'got_it' => 'Entendido',

    // Updates
    'update_available' => 'Actualización Disponible',
    'update_description' => 'Una nueva versión de la aplicación está disponible',
    'update_now' => 'Actualizar Ahora',
    'update_later' => 'Más Tarde',

    // Offline page
    'offline_title' => 'Estás Sin Conexión',
    'offline_subtitle' => 'Parece que has perdido tu conexión a internet. No te preocupes, aún puedes acceder a algunas funciones del panel de administración.',
    'offline_status' => 'Sin conexión',
    'online_status' => 'En línea',
    'offline_indicator' => 'Sin conexión a internet',

    // Features
    'available_features' => 'Funciones Disponibles',
    'feature_cached_pages' => 'Acceder a páginas y datos en caché',
    'feature_offline_forms' => 'Llenar formularios (se sincronizarán cuando esté en línea)',
    'feature_local_storage' => 'Ver información almacenada localmente',
    'feature_auto_sync' => 'Sincronización automática cuando regrese la conexión',

    // Actions
    'retry_connection' => 'Intentar de Nuevo',
    'go_home' => 'Ir al Inicio',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'Manifiesto de aplicación web no encontrado',
        'service_worker_missing' => 'Service worker no encontrado',
        'icons_missing' => 'Iconos PWA requeridos no encontrados',
        'https_required' => 'HTTPS es requerido para PWA en producción',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Configurando Plugin Filament PWA...',
        'publishing_assets' => 'Publicando recursos PWA...',
        'generating_icons' => 'Generando iconos PWA...',
        'validating' => 'Validando configuración PWA...',
        'completed' => '¡Configuración de Filament PWA completada!',
        'assets_published' => '¡Recursos publicados exitosamente!',
        'icons_generated' => '¡Iconos generados exitosamente!',
        'validation_passed' => '¡Todos los recursos PWA están presentes!',
        'validation_failed' => 'Validación PWA falló:',
        'source_not_found' => 'Imagen fuente no encontrada',
        'provide_source' => 'Por favor proporciona una imagen fuente usando --source=path/to/image.svg o --source=path/to/image.png',
        'svg_detected' => 'SVG fuente detectado - usando conversión vectorial de alta calidad',
        'raster_detected' => 'Imagen raster detectada - usando procesamiento de imágenes',
        'imagick_unavailable' => 'Extensión Imagick no disponible. Recurriendo a GD con conversión SVG.',
        'intervention_unavailable' => 'Intervention Image no instalado. Usando respaldo GD.',
        'gd_fallback' => 'Usando respaldo GD para procesamiento de imágenes (calidad limitada)',
        'install_imagick' => 'Por favor instala la extensión Imagick o Intervention Image para mejor calidad.',
    ],
];
