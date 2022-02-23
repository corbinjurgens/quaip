<?php

namespace Corbinjurgens\Quaip;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use Illuminate\Routing\Router;

class ServiceProvider extends BaseServiceProvider
{
	
	static $name = 'quaip';
	
    /**
     * Register services.
     *
     * @return void
     */
    public function register(){
      // config
      $this->mergeConfigFrom(
        __DIR__.'/config/quaip.php', 'quaip'
      );
      
      $this->app->singleton(self::$name, QuaipContainer::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(){
      $router = $this->app->make(Router::class);
      //$router->pushMiddlewareToGroup('web', Middleware::class);
      $router->aliasMiddleware('quaip', Middleware::class);

      $this->publishes([
        __DIR__.'/database/migrations' => database_path('migrations'),
      ], self::$name.'-migrations');
    
      $this->publishes([
        __DIR__.'/config/quaip.php' => config_path('quaip.php'),
      ], self::$name. '-config');
      
    }
}
