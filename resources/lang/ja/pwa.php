<?php

return [
    // Installation prompts
    'install_title' => 'アプリをインストール',
    'install_description' => 'インストールされたアプリでより良い体験を得る',
    'install_button' => 'インストール',
    'dismiss_button' => '閉じる',

    // iOS installation
    'ios_install_title' => 'iOSにアプリをインストール',
    'ios_install_description' => 'このアプリをiOSにインストールするには：',
    'ios_step_1' => '共有ボタンをタップ',
    'ios_step_2' => '「ホーム画面に追加」を選択',
    'ios_step_3' => '「追加」をタップして確認',
    'got_it' => '了解',

    // Updates
    'update_available' => 'アップデート利用可能',
    'update_description' => 'アプリの新しいバージョンが利用可能です',
    'update_now' => '今すぐアップデート',
    'update_later' => '後で',

    // Offline page
    'offline_title' => 'オフラインです',
    'offline_subtitle' => 'インターネット接続が失われたようです。心配しないでください、管理パネルの一部の機能にはまだアクセスできます。',
    'offline_status' => 'オフライン',
    'online_status' => 'オンライン',
    'offline_indicator' => 'インターネット接続なし',

    // Features
    'available_features' => '利用可能な機能',
    'feature_cached_pages' => 'キャッシュされたページとデータにアクセス',
    'feature_offline_forms' => 'フォームの入力（オンライン時に同期されます）',
    'feature_local_storage' => 'ローカルに保存された情報を表示',
    'feature_auto_sync' => '接続復旧時の自動同期',

    // Actions
    'retry_connection' => '再試行',
    'go_home' => 'ホームに戻る',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'ウェブアプリマニフェストが見つかりません',
        'service_worker_missing' => 'サービスワーカーが見つかりません',
        'icons_missing' => '必要なPWAアイコンが見つかりません',
        'https_required' => '本番環境でPWAにはHTTPSが必要です',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Filament PWAプラグインをセットアップ中...',
        'publishing_assets' => 'PWAアセットを公開中...',
        'generating_icons' => 'PWAアイコンを生成中...',
        'validating' => 'PWAセットアップを検証中...',
        'completed' => 'Filament PWAセットアップが完了しました！',
        'assets_published' => 'アセットが正常に公開されました！',
        'icons_generated' => 'アイコンが正常に生成されました！',
        'validation_passed' => 'すべてのPWAアセットが存在します！',
        'validation_failed' => 'PWA検証に失敗しました：',
        'source_not_found' => 'ソース画像が見つかりません',
        'provide_source' => '--source=path/to/image.svg または --source=path/to/image.png を使用してソース画像を提供してください',
        'svg_detected' => 'SVGソースを検出 - 高品質ベクター変換を使用',
        'raster_detected' => 'ラスター画像を検出 - 画像処理を使用',
        'imagick_unavailable' => 'Imagick拡張が利用できません。SVG変換でGDにフォールバック。',
        'intervention_unavailable' => 'Intervention Imageがインストールされていません。GDフォールバックを使用。',
        'gd_fallback' => '画像処理にGDフォールバックを使用（品質制限あり）',
        'install_imagick' => 'より良い品質のためにImagick拡張またはIntervention Imageをインストールしてください。',
    ],
];
