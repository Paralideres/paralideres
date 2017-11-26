<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        View::composer('layouts.common.footer', function ($view) {
            $categoriesCollections = Category::with(['collections' => function($q){
//                    $q->limit(20);
                }])
                ->whereExists(function ($query) {
                    $query->select('category_id')
                        ->from('collections')
                        ->whereRaw('collections.category_id = categories.id');
                })
//                ->limit(6)
                ->get();
            $view->with('categoriesCollections', $categoriesCollections);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
