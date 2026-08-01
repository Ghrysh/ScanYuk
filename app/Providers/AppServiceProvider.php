<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use App\Models\PageSeoContent;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
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