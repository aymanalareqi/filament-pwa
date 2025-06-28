<?php

return [
    // Installation prompts
    'install_title' => 'تثبيت التطبيق',
    'install_description' => 'احصل على تجربة أفضل مع التطبيق المثبت',
    'install_button' => 'تثبيت',
    'dismiss_button' => 'إغلاق',

    // iOS installation
    'ios_install_title' => 'تثبيت التطبيق على iOS',
    'ios_install_description' => 'لتثبيت هذا التطبيق على iOS:',
    'ios_step_1' => 'اضغط على زر المشاركة',
    'ios_step_2' => 'اختر "إضافة إلى الشاشة الرئيسية"',
    'ios_step_3' => 'اضغط "إضافة" للتأكيد',
    'got_it' => 'فهمت',

    // Updates
    'update_available' => 'تحديث متوفر',
    'update_description' => 'إصدار جديد من التطبيق متوفر',
    'update_now' => 'تحديث الآن',
    'update_later' => 'لاحقاً',

    // Offline page
    'offline_title' => 'أنت غير متصل',
    'offline_subtitle' => 'يبدو أنك فقدت اتصالك بالإنترنت. لا تقلق، يمكنك الوصول إلى بعض ميزات لوحة الإدارة.',
    'offline_status' => 'غير متصل',
    'online_status' => 'متصل',
    'offline_indicator' => 'لا يوجد اتصال بالإنترنت',

    // Features
    'available_features' => 'الميزات المتاحة',
    'feature_cached_pages' => 'الوصول إلى الصفحات والبيانات المحفوظة',
    'feature_offline_forms' => 'ملء النماذج (سيتم المزامنة عند الاتصال)',
    'feature_local_storage' => 'عرض المعلومات المحفوظة محلياً',
    'feature_auto_sync' => 'مزامنة تلقائية عند عودة الاتصال',

    // Actions
    'retry_connection' => 'حاول مرة أخرى',
    'go_home' => 'الذهاب للرئيسية',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'ملف بيان تطبيق الويب غير موجود',
        'service_worker_missing' => 'عامل الخدمة غير موجود',
        'icons_missing' => 'أيقونات PWA المطلوبة غير موجودة',
        'https_required' => 'HTTPS مطلوب لـ PWA في الإنتاج',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'إعداد إضافة Filament PWA...',
        'publishing_assets' => 'نشر أصول PWA...',
        'generating_icons' => 'إنشاء أيقونات PWA...',
        'validating' => 'التحقق من إعداد PWA...',
        'completed' => 'تم إكمال إعداد Filament PWA!',
        'assets_published' => 'تم نشر الأصول بنجاح!',
        'icons_generated' => 'تم إنشاء الأيقونات بنجاح!',
        'validation_passed' => 'جميع أصول PWA موجودة!',
        'validation_failed' => 'فشل التحقق من PWA:',
        'source_not_found' => 'الصورة المصدر غير موجودة',
        'provide_source' => 'يرجى توفير صورة مصدر باستخدام --source=path/to/image.svg أو --source=path/to/image.png',
        'svg_detected' => 'تم اكتشاف مصدر SVG - استخدام تحويل متجه عالي الجودة',
        'raster_detected' => 'تم اكتشاف صورة نقطية - استخدام معالجة الصور',
        'imagick_unavailable' => 'إضافة Imagick غير متوفرة. العودة إلى GD مع تحويل SVG.',
        'intervention_unavailable' => 'Intervention Image غير مثبت. استخدام GD البديل.',
        'gd_fallback' => 'استخدام GD البديل لمعالجة الصور (جودة محدودة)',
        'install_imagick' => 'يرجى تثبيت إضافة Imagick أو Intervention Image للحصول على جودة أفضل.',
    ],
];
