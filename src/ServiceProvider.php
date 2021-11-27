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
    public function register()
    {
		
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
    public function boot()
    {
		$router = $this->app->make(Router::class);
		$router->pushMiddlewareToGroup('web', Location::class);

		$this->loadMigrationsFrom(__DIR__.'/database/migrations');
			$this->publishes([
				__DIR__.'/database/migrations' => database_path('migrations'),
			], self::$name.'-migrations');
		
		/*
        $this->loadViewComponentsAs(self::$name, [
			Error::class,
			Input::class,
			Submit::class,
		]);
	   $this->loadViewsFrom(__DIR__.'/resources/views', self::$name);
		    $this->publishes([
				__DIR__.'/resources/views' => resource_path('views/vendor/' . self::$name),
			], self::$name . '-views');
	   */
	   
	   
	   
	   
	  
			$this->publishes([
				__DIR__.'/config/quaip.php' => config_path('quaip.php'),
			], self::$name. '-config');
		 /*
		// db
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');
			$this->publishes([
				__DIR__.'/database/migrations' => database_path('migrations'),
			], 'x-migrations');
		
		// lang
		$this->loadTranslationsFrom(__DIR__.'/resources/lang', 'x');
			$this->publishes([
				__DIR__.'/resources/lang' => resource_path('lang/vendor/x'),
			], 'x-lang');
		
		// views
		$this->loadViewsFrom(__DIR__.'/resources/views', 'x');
		    $this->publishes([
				__DIR__.'/resources/views' => resource_path('views/vendor/x'),
			], 'x-views');
			
		// commands
		if ($this->app->runningInConsole()) {
			$this->commands([
				MailIncoming::class,
			]);
		}
		*/
		
    }
}
