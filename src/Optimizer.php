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
	* String containing the original name of the file we are optimizing.
	*
	* @var String
	*/
	private $originalFile;
	
	/**
	* String containing a temporary name for the optimized file (before saving).
	*
	* @var String
	*/
	private $tmpFile = '';
	
	/**
	* These variables hold the real paths to binaries
	*/
	private $pngquant;
	private $optipng;
	
	/**
	 * Create a new Optimizer instance.
	 *
	 * @param  \Illuminate\Config\Repository  $config
	 */
	public function __construct(Repository $config) {
		$this->config = $config;
		
		$os_platform = 'linux/';
		$ext = '';
		if( substr(PHP_OS, 0, 3) == 'win' ) {
			$os_platform = 'windows/';
			$ext = '.exe';
		} else if( substr(PHP_OS, 0, 3) == 'mac' ) {
			$os_platform = 'mac/';
		}
		
		$binDir = dirname(dirname(__FILE__)) . '/bin/' . $os_platform;
		
		$this->pngquant = $binDir . 'pngquant/pngquant' . $ext;
		$this->optipng = $binDir . 'optipng/optipng' . $ext;
	}
	
	/**
	 * Optimize the file?
	 *
	 * @param  File?  $file
	 */
	public function optimize($file) {
		if( !empty( $this->tmpFile ) ) {
			// We have an unsaved optimized file... Scrap it
			unlink( $this->tmpFile );
			$this->tmpFile = '';
		}
		
		$this->originalFile = realpath($file);
		
		if( $this->originalFile !== false ) {
			$extension = substr( $file, strrpos( $file, '.' ) + 1 );
			
			switch($extension) {
				case 'png':
					$this->optimizePNG();
					break;
				default:
					// Error?
			}
		}
	}
	
	/**
	 * Optimize the file?
	 *
	 * @param  NONE
	 */
	private function optimizePNG() {
		$this->tmpFile = $this->generateTmpFileName();
		
		// Pass it first to pngquant
		$parameters = ' --force --output ' . $this->tmpFile;
		if( $this->config->get('optimizer.pngquant.skip-if-larger') )
			$parameters .= ' --skip-if-larger ';
		$parameters .= ' --quality ' . $this->config->get('optimizer.pngquant.quality');
		$parameters .= ' --speed ' . $this->config->get('optimizer.pngquant.speed');
		if( $this->config->get('optimizer.pngquant.nofs') )
			$parameters .= ' --nofs ' . $this->config->get('optimizer.pngquant.nofs');
		$parameters .= ' --posterize ' . $this->config->get('optimizer.pngquant.posterize');
		
		exec( $this->pngquant . $parameters . ' ' . $this->originalFile );
		
		// ... Then to optiPNG?
		
		// Maybe we can do a little bit more, too? Like reducing dimensions to
		// match a min/max in the config
	}
	
	/**
	 * Generate a unique name for saving optimized files temporarily
	 *
	 * @param  NONE
	 * 
	 * @return  String
	 */
	private function generateTmpFileName() {
		$tmpFile = substr(
			str_shuffle(
				str_repeat(
					$this->config->get('optimizer.tmpFile.charset'),
					$this->config->get('optimizer.tmpFile.length')
				)
			), 0, $this->config->get('optimizer.tmpFile.length')
		);
		while( file_exists( storage_path() . '/' . $this->config->get('optimizer.tmpFile.folder') . '/' . $tmpFile ) ) {
			$tmpFile = substr(
				str_shuffle(
					str_repeat(
						$this->config->get('optimizer.tmpFile.charset'),
						$this->config->get('optimizer.tmpFile.length')
					)
				), 0, $this->config->get('optimizer.tmpFile.length')
			);
		}
		return storage_path() . '/' . $this->config->get('optimizer.tmpFile.folder') . '/' . $tmpFile;
	}
}
