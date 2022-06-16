<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Services;
class RegisterationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserService::class, function(){
            return new UserService();
          });
          $this->app->bind(ProductService::class, function(){
            return new ProductService();
          });
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
