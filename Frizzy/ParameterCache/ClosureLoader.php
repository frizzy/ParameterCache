<?php

namespace Frizzy\ParameterCache;

use Closure;

/**
 * ClosureLoader
 */
class ClosureLoader extends LoaderAbstract
{
    private $loaderClosure;
    
    /**
     * Construct
     *
     * @param Closure $loaderClosure Loader closure
     * @param string  $cacheDirectory Cache directory
     */
    public function __construct(Closure $loaderClosure, $cacheDirectory = null)
    {
        $this->loaderClosure = $loaderClosure;
        parent::__construct($cacheDirectory);
    }
    
    /**
     * Load file
     *
     * {@inheritDoc}
     */
    protected function loadFile($file)
    {
        return $this->loaderClosure->__invoke($file);
    }
}
