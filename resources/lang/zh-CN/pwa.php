<?php

return [
    // Installation prompts
    'install_title' => '安装应用',
    'install_description' => '通过安装的应用获得更好的体验',
    'install_button' => '安装',
    'dismiss_button' => '忽略',

    // iOS installation
    'ios_install_title' => '在iOS上安装应用',
    'ios_install_description' => '要在iOS上安装此应用：',
    'ios_step_1' => '点击分享按钮',
    'ios_step_2' => '选择"添加到主屏幕"',
    'ios_step_3' => '点击"添加"确认',
    'got_it' => '知道了',

    // Updates
    'update_available' => '有可用更新',
    'update_description' => '应用的新版本可用',
    'update_now' => '立即更新',
    'update_later' => '稍后',

    // Offline page
    'offline_title' => '您已离线',
    'offline_subtitle' => '看起来您失去了互联网连接。别担心，您仍然可以访问管理面板的一些功能。',
    'offline_status' => '离线',
    'online_status' => '在线',
    'offline_indicator' => '无互联网连接',

    // Features
    'available_features' => '可用功能',
    'feature_cached_pages' => '访问缓存的页面和数据',
    'feature_offline_forms' => '填写表单（在线时将同步）',
    'feature_local_storage' => '查看本地存储的信息',
    'feature_auto_sync' => '连接恢复时自动同步',

    // Actions
    'retry_connection' => '重试',
    'go_home' => '回到首页',

    // Validation messages
    'validation' => [
        'manifest_missing' => '未找到Web应用清单',
        'service_worker_missing' => '未找到Service Worker',
        'icons_missing' => '未找到必需的PWA图标',
        'https_required' => '生产环境中PWA需要HTTPS',
    ],

    // Setup command messages
    'setup' => [
        'starting' => '正在设置Filament PWA插件...',
        'publishing_assets' => '正在发布PWA资源...',
        'generating_icons' => '正在生成PWA图标...',
        'validating' => '正在验证PWA设置...',
        'completed' => 'Filament PWA设置完成！',
        'assets_published' => '资源发布成功！',
        'icons_generated' => '图标生成成功！',
        'validation_passed' => '所有PWA资源都存在！',
        'validation_failed' => 'PWA验证失败：',
        'source_not_found' => '未找到源图像',
        'provide_source' => '请使用 --source=path/to/image.svg 或 --source=path/to/image.png 提供源图像',
        'svg_detected' => '检测到SVG源 - 使用高质量矢量转换',
        'raster_detected' => '检测到栅格图像 - 使用图像处理',
        'imagick_unavailable' => 'Imagick扩展不可用。回退到GD与SVG转换。',
        'intervention_unavailable' => 'Intervention Image未安装。使用GD回退。',
        'gd_fallback' => '使用GD回退进行图像处理（质量有限）',
        'install_imagick' => '请安装Imagick扩展或Intervention Image以获得更好的质量。',
    ],
];
