# Internationalization Guide

This guide covers the comprehensive internationalization (i18n) features of the Filament PWA plugin, including built-in translations for 10+ languages and full RTL/LTR support.

## Table of Contents

- [Overview](#overview)
- [Supported Languages](#supported-languages)
- [Automatic Detection](#automatic-detection)
- [Manual Configuration](#manual-configuration)
- [RTL Language Support](#rtl-language-support)
- [Custom Translations](#custom-translations)
- [Adding New Languages](#adding-new-languages)
- [Translation Keys Reference](#translation-keys-reference)
- [Best Practices](#best-practices)

## Overview

The Filament PWA plugin includes comprehensive internationalization support with:

- **Built-in translations** for 10+ languages
- **Automatic language detection** from Laravel's locale
- **Automatic text direction detection** for RTL languages
- **Full RTL support** with culturally appropriate translations
- **Dynamic language switching** based on user preferences
- **Easy customization** of existing translations
- **Simple addition** of new languages

## Supported Languages

The plugin includes complete translations for the following languages:

| Language | Code | Direction | Script | Status |
|----------|------|-----------|--------|--------|
| English | `en` | LTR | Latin | ✅ Complete |
| Arabic | `ar` | RTL | Arabic | ✅ Complete |
| Spanish | `es` | LTR | Latin | ✅ Complete |
| French | `fr` | LTR | Latin | ✅ Complete |
| German | `de` | LTR | Latin | ✅ Complete |
| Portuguese | `pt` | LTR | Latin | ✅ Complete |
| Italian | `it` | LTR | Latin | ✅ Complete |
| Russian | `ru` | LTR | Cyrillic | ✅ Complete |
| Japanese | `ja` | LTR | Japanese | ✅ Complete |
| Chinese (Simplified) | `zh-CN` | LTR | Chinese | ✅ Complete |
| Dutch | `nl` | LTR | Latin | ✅ Complete |

### Translation Coverage

Each language includes translations for:
- Installation prompts and buttons
- iOS-specific installation instructions
- Update notifications
- Offline functionality messages
- Feature descriptions
- Validation error messages
- Setup command messages

## Automatic Detection

The plugin automatically detects language and text direction from your Laravel application configuration.

### Language Detection

```php
// Laravel app configuration (config/app.php)
'locale' => 'ar',

// The PWA plugin automatically detects and uses Arabic
FilamentPwaPlugin::make()
    // No language configuration needed - automatically uses 'ar'
```

### Text Direction Detection

The plugin automatically detects RTL languages and sets the appropriate text direction:

```php
// RTL languages automatically detected:
$rtlLanguages = ['ar', 'he', 'fa', 'ur', 'ku', 'dv', 'ps', 'sd', 'yi'];

// If Laravel locale is 'ar', PWA automatically sets dir="rtl"
```

### Smart Defaults

When automatic detection is not possible, the plugin falls back to sensible defaults:
- **Language**: `'en'` (English)
- **Direction**: `'ltr'` (Left-to-right)

## Manual Configuration

You can override automatic detection with manual configuration.

### Static Language Configuration

```php
FilamentPwaPlugin::make()
    ->language('ar')                           // Set Arabic
    ->direction('rtl')                         // Set right-to-left
    
    // Convenience methods
    ->rtl()                                    // Same as direction('rtl')
    ->ltr()                                    // Same as direction('ltr')
```

### Dynamic Language Configuration

Use closures for user-specific language preferences:

```php
FilamentPwaPlugin::make()
    ->language(fn() => auth()->user()?->language ?? app()->getLocale())
    ->direction(fn() => auth()->user()?->text_direction ?? 'ltr')
```

### Environment-based Configuration

```php
FilamentPwaPlugin::make()
    ->language(fn() => match(app()->environment()) {
        'production' => 'en',
        'staging' => 'es',
        default => app()->getLocale()
    })
```

## RTL Language Support

The plugin provides comprehensive RTL (Right-to-Left) language support.

### Automatic RTL Detection

RTL languages are automatically detected and configured:

```php
// Supported RTL languages
$rtlLanguages = [
    'ar',  // Arabic
    'he',  // Hebrew
    'fa',  // Persian/Farsi
    'ur',  // Urdu
    'ku',  // Kurdish
    'dv',  // Divehi
    'ps',  // Pashto
    'sd',  // Sindhi
    'yi',  // Yiddish
];
```

### RTL Features

For RTL languages, the plugin automatically:

1. **Sets PWA manifest direction**: `"dir": "rtl"`
2. **Provides culturally appropriate translations**
3. **Handles text direction in UI components**
4. **Supports RTL-aware installation prompts**

### Arabic Language Example

```php
// Set Laravel locale to Arabic
app()->setLocale('ar');

FilamentPwaPlugin::make()
    // Automatically detects Arabic and sets:
    // - language: 'ar'
    // - direction: 'rtl'
    // - Uses Arabic translations from resources/lang/ar/pwa.php
```

### Manual RTL Configuration

```php
FilamentPwaPlugin::make()
    ->language('ar')
    ->rtl()                                    // Explicitly set RTL
```

## Custom Translations

You can customize existing translations or add new ones.

### Publishing Translation Files

First, publish the language files:

```bash
php artisan vendor:publish --tag="filament-pwa-lang"
```

This creates translation files in `resources/lang/{locale}/pwa.php`.

### Customizing Existing Translations

Edit the published translation files:

```php
// resources/lang/ar/pwa.php
return [
    'install_title' => 'تثبيت التطبيق المخصص',  // Custom Arabic translation
    'install_description' => 'احصل على تجربة محسنة مع التطبيق المثبت',
    'install_button' => 'تثبيت الآن',
    'dismiss_button' => 'إغلاق',
    // ... rest of translations
];
```

### Overriding Specific Keys

You can override specific translation keys without publishing all files:

```php
// In a service provider
public function boot()
{
    // Override specific Arabic translations
    Lang::addLines([
        'filament-pwa::pwa.install_title' => 'تثبيت التطبيق المخصص',
        'filament-pwa::pwa.install_description' => 'وصف مخصص للتثبيت',
    ], 'ar');
}
```

## Adding New Languages

To add support for a new language:

### Step 1: Create Translation File

```bash
# Copy the English template
cp resources/lang/en/pwa.php resources/lang/your-locale/pwa.php
```

### Step 2: Translate Content

Edit the new file and translate all strings:

```php
// resources/lang/your-locale/pwa.php
return [
    // Installation prompts
    'install_title' => 'Your Translation',
    'install_description' => 'Your Translation',
    'install_button' => 'Your Translation',
    'dismiss_button' => 'Your Translation',
    
    // iOS installation
    'ios_install_title' => 'Your Translation',
    'ios_install_description' => 'Your Translation',
    'ios_step_1' => 'Your Translation',
    'ios_step_2' => 'Your Translation',
    'ios_step_3' => 'Your Translation',
    'got_it' => 'Your Translation',
    
    // ... continue with all keys
];
```

### Step 3: Configure Language

```php
FilamentPwaPlugin::make()
    ->language('your-locale')
    ->direction('ltr') // or 'rtl' for RTL languages
```

### Step 4: Test Translations

Test your translations by setting the Laravel locale:

```php
app()->setLocale('your-locale');
```

## Translation Keys Reference

### Installation Prompts
- `install_title` - Main installation prompt title
- `install_description` - Installation prompt description
- `install_button` - Install button text
- `dismiss_button` - Dismiss button text

### iOS Installation
- `ios_install_title` - iOS installation modal title
- `ios_install_description` - iOS installation description
- `ios_step_1` - First step instruction
- `ios_step_2` - Second step instruction
- `ios_step_3` - Third step instruction
- `got_it` - Confirmation button

### Updates
- `update_available` - Update notification title
- `update_description` - Update description
- `update_now` - Update button text
- `update_later` - Later button text

### Offline Functionality
- `offline_title` - Offline page title
- `offline_subtitle` - Offline page description
- `offline_status` - Offline status indicator
- `online_status` - Online status indicator
- `offline_indicator` - No connection message

### Features
- `available_features` - Available features title
- `feature_cached_pages` - Cached pages description
- `feature_offline_forms` - Offline forms description
- `feature_local_storage` - Local storage description
- `feature_auto_sync` - Auto sync description

### Actions
- `retry_connection` - Retry connection button
- `go_home` - Go home button

### Validation Messages
- `validation.manifest_missing` - Manifest not found error
- `validation.service_worker_missing` - Service worker not found error
- `validation.icons_missing` - Icons not found error
- `validation.https_required` - HTTPS required error

### Setup Command Messages
- `setup.starting` - Setup starting message
- `setup.publishing_assets` - Publishing assets message
- `setup.generating_icons` - Generating icons message
- `setup.validating` - Validating setup message
- `setup.completed` - Setup completed message
- `setup.assets_published` - Assets published message
- `setup.icons_generated` - Icons generated message
- `setup.validation_passed` - Validation passed message
- `setup.validation_failed` - Validation failed message

## Best Practices

### 1. Preserve Array Structure

Always maintain the exact array structure when translating:

```php
// ✅ Correct - maintains structure
'validation' => [
    'manifest_missing' => 'Your translation',
    'service_worker_missing' => 'Your translation',
],

// ❌ Incorrect - changes structure
'validation_manifest_missing' => 'Your translation',
```

### 2. Preserve Placeholder Variables

Keep placeholder variables unchanged:

```php
// ✅ Correct - preserves :attribute placeholder
'validation_failed' => 'PWA validation failed: :attribute',

// ❌ Incorrect - removes placeholder
'validation_failed' => 'PWA validation failed',
```

### 3. Cultural Appropriateness

Ensure translations are culturally appropriate:

```php
// For Arabic - use formal, respectful language
'install_description' => 'احصل على تجربة أفضل مع التطبيق المثبت',

// For Japanese - use polite forms
'install_description' => 'インストールされたアプリでより良い体験を得る',
```

### 4. RTL Language Considerations

For RTL languages:
- Use appropriate punctuation (Arabic comma ، vs Latin comma ,)
- Consider text flow and reading patterns
- Test UI layout with RTL text

### 5. Testing Translations

Always test translations in context:

```php
// Test with different locales
app()->setLocale('ar');
// Check PWA installation prompts

app()->setLocale('ja');
// Check PWA installation prompts
```

### 6. Consistency

Maintain consistency across all translation keys:
- Use the same terminology throughout
- Keep tone and style consistent
- Follow language-specific conventions
