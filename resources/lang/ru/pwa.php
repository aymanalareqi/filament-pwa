<?php

return [
    // Installation prompts
    'install_title' => 'Установить Приложение',
    'install_description' => 'Получите лучший опыт с установленным приложением',
    'install_button' => 'Установить',
    'dismiss_button' => 'Отклонить',

    // iOS installation
    'ios_install_title' => 'Установить Приложение на iOS',
    'ios_install_description' => 'Чтобы установить это приложение на iOS:',
    'ios_step_1' => 'Нажмите кнопку Поделиться',
    'ios_step_2' => 'Выберите "Добавить на экран Домой"',
    'ios_step_3' => 'Нажмите "Добавить" для подтверждения',
    'got_it' => 'Понятно',

    // Updates
    'update_available' => 'Доступно Обновление',
    'update_description' => 'Доступна новая версия приложения',
    'update_now' => 'Обновить Сейчас',
    'update_later' => 'Позже',

    // Offline page
    'offline_title' => 'Вы Не В Сети',
    'offline_subtitle' => 'Похоже, вы потеряли интернет-соединение. Не волнуйтесь, вы все еще можете получить доступ к некоторым функциям панели администратора.',
    'offline_status' => 'Не в сети',
    'online_status' => 'В сети',
    'offline_indicator' => 'Нет интернет-соединения',

    // Features
    'available_features' => 'Доступные Функции',
    'feature_cached_pages' => 'Доступ к кэшированным страницам и данным',
    'feature_offline_forms' => 'Заполнение форм (синхронизируется при подключении)',
    'feature_local_storage' => 'Просмотр локально сохраненной информации',
    'feature_auto_sync' => 'Автоматическая синхронизация при восстановлении соединения',

    // Actions
    'retry_connection' => 'Попробовать Снова',
    'go_home' => 'На Главную',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'Манифест веб-приложения не найден',
        'service_worker_missing' => 'Service worker не найден',
        'icons_missing' => 'Необходимые иконки PWA не найдены',
        'https_required' => 'HTTPS требуется для PWA в продакшене',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Настройка плагина Filament PWA...',
        'publishing_assets' => 'Публикация ресурсов PWA...',
        'generating_icons' => 'Генерация иконок PWA...',
        'validating' => 'Проверка настройки PWA...',
        'completed' => 'Настройка Filament PWA завершена!',
        'assets_published' => 'Ресурсы успешно опубликованы!',
        'icons_generated' => 'Иконки успешно сгенерированы!',
        'validation_passed' => 'Все ресурсы PWA присутствуют!',
        'validation_failed' => 'Проверка PWA не удалась:',
        'source_not_found' => 'Исходное изображение не найдено',
        'provide_source' => 'Пожалуйста, предоставьте исходное изображение, используя --source=path/to/image.svg или --source=path/to/image.png',
        'svg_detected' => 'Обнаружен SVG источник - используется высококачественное векторное преобразование',
        'raster_detected' => 'Обнаружено растровое изображение - используется обработка изображений',
        'imagick_unavailable' => 'Расширение Imagick недоступно. Переход на GD с преобразованием SVG.',
        'intervention_unavailable' => 'Intervention Image не установлен. Используется резервный GD.',
        'gd_fallback' => 'Используется резервный GD для обработки изображений (ограниченное качество)',
        'install_imagick' => 'Пожалуйста, установите расширение Imagick или Intervention Image для лучшего качества.',
    ],
];
