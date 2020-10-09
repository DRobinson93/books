<?php

namespace App\Providers;

use App\Interfaces\QueryBook;
use App\Services\BookApiHelper;
use Illuminate\Support\ServiceProvider;

class QueryBookServiceProvider extends ServiceProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        QueryBook::class => BookApiHelper::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
