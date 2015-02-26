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
		
		// Detect operating system... If Linux, we'll need to compile our
		// dependencies from source
		$os_platform = 'unknown';
		$os_array = array(
			'/win/i'           =>  'windows',
			'/mac/i'           =>  'mac',
			'/lin|bsd|unix/i'  =>  'linux'
		);
		foreach ($os_array as $regex => $value) {
			if (preg_match($regex, PHP_OS)) {
				$os_platform = $value;
				break;
			}
		}
		
		if( $os_platform == 'linux' ) {
			// Compile pngquant
			if(!file_exists(dirname(dirname(__FILE__) . '/bin/linux/pngquant/pngquant'))) {
				$returnVal = exec('which gcc');
				if(empty($returnVal)) {
					// Error - gcc not installed
				}
				
				$returnVal = exec('ldconfig -p | grep "libpng"');
				if(empty($returnVal)) {
					// Error - libpng not installed
				}
				
				$returnVal = exec('ldconfig -p | grep "libz"');
				if(empty($returnVal)) {
					// Error - zlib not installed
				}
				
				$returnVal = exec('cd ' . dirname(dirname(__FILE__)) . '/bin/linux/pngquant/src && make');
			}
		}
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
			return new WorldDistance($app['config']);
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
