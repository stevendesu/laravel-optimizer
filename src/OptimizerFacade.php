<?php namespace Stevendesu\Optimizer;

use Illuminate\Support\Facades\Facade;

class OptimizerFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'optimizer'; }

}