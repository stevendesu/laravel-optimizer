<?php namespace Stevendesu\Optimizer;

use Illuminate\Support\ServiceProvider;

class OptimizerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Publish config file
		$this->publishes([
			__DIR__.'/../config/optimizer.php' => config_path('optimizer.php'),
		]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Register providers.
		$this->app['optimizer'] = $this->app->share(function($app)
		{
			return new Optimizer($app['config']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['optimizer'];
	}

}
