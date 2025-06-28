<?php

namespace Alareqi\FilamentPwa\Http\Controllers;

use Alareqi\FilamentPwa\Services\PwaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class PwaController extends Controller
{
    /**
     * Serve the PWA manifest file
     */
    public function manifest(): JsonResponse
    {
        $config = PwaService::getConfig();

        return response()->json($config, 200, [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Serve the service worker file
     */
    public function serviceWorker(): Response
    {
        // Check if custom service worker exists
        $customSwPath = public_path('sw.js');
        if (File::exists($customSwPath)) {
            $content = File::get($customSwPath);
        } else {
            // Generate service worker from template
            $swConfig = PwaService::getServiceWorkerConfig();
            $content = View::make('filament-pwa::service-worker', compact('swConfig'))->render();
        }

        return response($content, 200, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Serve the browser configuration file for Microsoft tiles
     */
    public function browserConfig(): Response
    {
        // Check if custom browserconfig exists
        $customConfigPath = public_path('browserconfig.xml');
        if (File::exists($customConfigPath)) {
            $content = File::get($customConfigPath);
        } else {
            // Generate browserconfig from template
            $config = PwaService::getConfig();
            $content = View::make('filament-pwa::browserconfig', compact('config'))->render();
        }

        return response($content, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Serve the offline fallback page
     */
    public function offline(): Response
    {
        $config = PwaService::getConfig();
        
        return response()->view('filament-pwa::offline', compact('config'), 200, [
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
