<?php namespace Stevendesu\Optimizer;

// For logging major fail
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// Default like units, base location, or Earth size
use Illuminate\Config\Repository;

class Optimizer {
    
	/**
	* Illuminate config repository instance.
	*
	* @var \Illuminate\Config\Repository
	*/
	protected $config;
	
	/**
	 * Create a new Optimizer instance.
	 *
	 * @param  \Illuminate\Config\Repository  $config
	 */
	public function __construct(Repository $config) {
		$this->config = $config;
	}
	
	/**
	 * Optimize the file?
	 *
	 * @param  File?  $file
	 */
	public function optimize($file) {
		//
	}
}
