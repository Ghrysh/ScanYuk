<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request; // Facade for Request::path()
use App\Models\PageSeoContent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request as HttpRequest; // For setTrustedProxies

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Production HTTPS & Proxy Logic
        if ($this->app->environment('production')) {
            HttpRequest::setTrustedProxies(
                ['0.0.0.0/0'],
                HttpRequest::HEADER_X_FORWARDED_FOR | HttpRequest::HEADER_X_FORWARDED_PROTO | HttpRequest::HEADER_X_FORWARDED_AWS_ELB
            );
            URL::forceScheme('https');
        }

        // Share SEO data to all views globally if the table exists
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('page_seo_contents')) {
                    // Normalize path, ensuring '/' instead of empty for root
                    $path = '/' . ltrim(Request::path(), '/');
                    if ($path === '//') $path = '/';
                    
                    $seoData = PageSeoContent::where('page_path', $path)->first();
                    $view->with('seoData', $seoData);
                } else {
                    $view->with('seoData', null);
                }
            } catch (\Exception $e) {
                $view->with('seoData', null);
            }
        });
    }
}