{{-- PWA Installation Script --}}
<script>
// PWA Installation Manager
class PWAInstaller {
    constructor() {
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.isStandalone = false;
        this.banner = null;
        this.config = @json($config);
        
        this.init();
    }

    init() {
        // Check if app is already installed
        this.checkInstallationStatus();
        
        // Listen for beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('[PWA] beforeinstallprompt event fired');
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallBanner();
        });

        // Listen for appinstalled event
        window.addEventListener('appinstalled', (e) => {
            console.log('[PWA] App was installed');
            this.isInstalled = true;
            this.hideInstallBanner();
            this.trackInstallation();
        });

        // Register service worker
        this.registerServiceWorker();

        // Create install banner
        this.createInstallBanner();

        // In debug mode, show banner immediately if enabled
        const isDebugMode = {{ config('app.debug') ? 'true' : 'false' }};
        const showBannerInDebug = this.config.installation?.show_banner_in_debug ?? true;
        if (isDebugMode && showBannerInDebug && this.banner) {
            console.log('[PWA] Debug mode: Showing banner immediately');
            this.showInstallBanner();
        }

        // Handle iOS installation
        this.handleIOSInstallation();
    }

    checkInstallationStatus() {
        // Check if running in standalone mode
        this.isStandalone = window.matchMedia('(display-mode: standalone)').matches ||
                          window.navigator.standalone === true;

        // Check if already installed
        this.isInstalled = this.isStandalone || 
                          localStorage.getItem('pwa-installed') === 'true';

        console.log('[PWA] Installation status:', {
            isStandalone: this.isStandalone,
            isInstalled: this.isInstalled
        });
    }

    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('{{ route("filament-pwa.service-worker") }}', {
                    scope: this.config.scope || '/admin'
                });
                
                console.log('[PWA] Service Worker registered successfully:', registration);

                // Handle service worker updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            this.showUpdateAvailable();
                        }
                    });
                });

            } catch (error) {
                console.error('[PWA] Service Worker registration failed:', error);
            }
        } else {
            console.log('[PWA] Service Worker not supported');
        }
    }

    createInstallBanner() {
        // Check if we should show banner in debug mode
        const isDebugMode = {{ config('app.debug') ? 'true' : 'false' }};
        const showBannerInDebug = this.config.installation?.show_banner_in_debug ?? true;

        // In debug mode, bypass installation and dismissal checks if debug banner is enabled
        if (isDebugMode && showBannerInDebug) {
            console.log('[PWA] Debug mode: Showing installation banner regardless of state');
        } else {
            // Normal logic: Don't show banner if already installed or disabled
            if (this.isInstalled || !this.config.installation_prompts?.enabled) return;
        }

        const banner = document.createElement('div');
        banner.className = 'pwa-install-banner';
        banner.innerHTML = `
            <div class="pwa-install-content">
                <div class="pwa-install-text">
                    <div class="pwa-install-title">{{ __('filament-pwa::pwa.install_title') }}</div>
                    <div class="pwa-install-description">{{ __('filament-pwa::pwa.install_description') }}</div>
                </div>
                <div class="pwa-install-actions">
                    <button class="pwa-install-btn primary" id="pwa-install-btn">
                        <svg class="pwa-install-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('filament-pwa::pwa.install_button') }}
                    </button>
                    <button class="pwa-install-btn" id="pwa-dismiss-btn">
                        <svg class="pwa-install-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('filament-pwa::pwa.dismiss_button') }}
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(banner);
        this.banner = banner;

        // Add event listeners
        document.getElementById('pwa-install-btn').addEventListener('click', () => {
            this.installApp();
        });

        document.getElementById('pwa-dismiss-btn').addEventListener('click', () => {
            this.dismissInstallBanner();
        });
    }

    showInstallBanner() {
        if (!this.banner) return;

        // Check if we should show banner in debug mode
        const isDebugMode = {{ config('app.debug') ? 'true' : 'false' }};
        const showBannerInDebug = this.config.installation?.show_banner_in_debug ?? true;

        // In debug mode, bypass all checks if debug banner is enabled
        if (isDebugMode && showBannerInDebug) {
            console.log('[PWA] Debug mode: Bypassing dismissal and installation checks');
            const delay = this.config.installation?.prompt_delay || this.config.installation_prompts?.delay || 2000;
            setTimeout(() => {
                this.banner.classList.add('show');
            }, delay);
            return;
        }

        // Normal logic: Check installation status and dismissal
        if (this.isInstalled) return;

        // Don't show if user dismissed recently
        const dismissed = localStorage.getItem('pwa-banner-dismissed');
        if (dismissed && Date.now() - parseInt(dismissed) < 7 * 24 * 60 * 60 * 1000) {
            return;
        }

        const delay = this.config.installation_prompts?.delay || 2000;
        setTimeout(() => {
            this.banner.classList.add('show');
        }, delay);
    }

    hideInstallBanner() {
        if (this.banner) {
            this.banner.classList.remove('show');
        }
    }

    dismissInstallBanner() {
        this.hideInstallBanner();
        localStorage.setItem('pwa-banner-dismissed', Date.now().toString());
    }

    async installApp() {
        if (!this.deferredPrompt) {
            console.log('[PWA] No deferred prompt available');
            return;
        }

        try {
            // Show the install prompt
            this.deferredPrompt.prompt();

            // Wait for the user to respond
            const { outcome } = await this.deferredPrompt.userChoice;
            
            console.log('[PWA] User choice:', outcome);

            if (outcome === 'accepted') {
                console.log('[PWA] User accepted the install prompt');
                this.hideInstallBanner();
            } else {
                console.log('[PWA] User dismissed the install prompt');
                this.dismissInstallBanner();
            }

            // Clear the deferred prompt
            this.deferredPrompt = null;

        } catch (error) {
            console.error('[PWA] Error during installation:', error);
        }
    }

    handleIOSInstallation() {
        // Check if iOS Safari
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        const isInStandaloneMode = window.navigator.standalone;

        if (isIOS && !isInStandaloneMode && !this.isInstalled) {
            // Show iOS-specific installation instructions
            const delay = this.config.installation_prompts?.ios_instructions_delay || 5000;
            setTimeout(() => {
                this.showIOSInstallInstructions();
            }, delay);
        }
    }

    showIOSInstallInstructions() {
        // Create iOS install modal
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 1rem;
        `;

        modal.innerHTML = `
            <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 400px; text-align: center; direction: ${this.config.dir || 'ltr'};">
                <h3 style="margin: 0 0 1rem 0; color: ${this.config.theme_color};">{{ __('filament-pwa::pwa.ios_install_title') }}</h3>
                <p style="margin: 0 0 1.5rem 0; color: #666; line-height: 1.5;">
                    {{ __('filament-pwa::pwa.ios_install_description') }}
                </p>
                <ol style="text-align: ${this.config.dir === 'rtl' ? 'right' : 'left'}; color: #666; line-height: 1.8; margin: 0 0 1.5rem 0;">
                    <li>{{ __('filament-pwa::pwa.ios_step_1') }} <span style="font-size: 1.2em;">⬆️</span></li>
                    <li>{{ __('filament-pwa::pwa.ios_step_2') }}</li>
                    <li>{{ __('filament-pwa::pwa.ios_step_3') }}</li>
                </ol>
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="background: ${this.config.theme_color}; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; cursor: pointer;">
                    {{ __('filament-pwa::pwa.got_it') }}
                </button>
            </div>
        `;

        document.body.appendChild(modal);

        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (modal.parentElement) {
                modal.remove();
            }
        }, 10000);
    }

    showUpdateAvailable() {
        // Show update notification with RTL/LTR support
        const notification = document.createElement('div');
        const isRTL = document.documentElement.dir === 'rtl';

        notification.style.cssText = `
            position: fixed;
            top: 1rem;
            ${isRTL ? 'left' : 'right'}: 1rem;
            background: ${this.config.theme_color};
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            max-width: 300px;
            direction: inherit;
        `;

        notification.innerHTML = `
            <div style="margin-bottom: 0.5rem; font-weight: bold;">{{ __('filament-pwa::pwa.update_available') }}</div>
            <div style="margin-bottom: 1rem; font-size: 0.875rem;">{{ __('filament-pwa::pwa.update_description') }}</div>
            <div style="display: flex; gap: 0.5rem; ${isRTL ? 'flex-direction: row-reverse;' : ''}">
                <button onclick="window.location.reload()"
                        style="background: white; color: ${this.config.theme_color}; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer;">
                    {{ __('filament-pwa::pwa.update_now') }}
                </button>
                <button onclick="this.parentElement.remove()"
                        style="background: transparent; color: white; border: 1px solid white; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer;">
                    {{ __('filament-pwa::pwa.update_later') }}
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 10000);
    }

    trackInstallation() {
        // Track PWA installation for analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pwa_install', {
                event_category: 'PWA',
                event_label: 'Admin Panel'
            });
        }

        // Store installation status
        localStorage.setItem('pwa-installed', 'true');
        localStorage.setItem('pwa-install-date', new Date().toISOString());
    }
}

// Initialize PWA installer when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new PWAInstaller();
    });
} else {
    new PWAInstaller();
}

// Handle online/offline status
window.addEventListener('online', () => {
    console.log('[PWA] Back online');
    document.body.classList.remove('offline');
});

window.addEventListener('offline', () => {
    console.log('[PWA] Gone offline');
    document.body.classList.add('offline');
});

// Add offline indicator styles with Tailwind CSS and RTL/LTR support
const offlineStyles = document.createElement('style');
offlineStyles.textContent = `
    .offline::before {
        content: "{{ __('filament-pwa::pwa.offline_indicator') }}";
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: #f59e0b;
        color: white;
        text-align: center;
        padding: 0.5rem;
        z-index: 9999;
        font-size: 0.875rem;
        direction: inherit;
    }

    .offline {
        padding-top: 2.5rem !important;
    }

    /* RTL/LTR support for offline indicator */
    [dir="rtl"] .offline::before {
        text-align: center;
    }

    [dir="ltr"] .offline::before {
        text-align: center;
    }
`;
document.head.appendChild(offlineStyles);
</script>
