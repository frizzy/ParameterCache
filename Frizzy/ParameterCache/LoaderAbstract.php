<?php

namespace Frizzy\ParameterCache;

use SplFileInfo;

abstract class LoaderAbstract
{
    private $cacheDirectory;
    
    /**
     * Construct
     *
     * @param string $cacheDirectory Cache directory
     */
    public function __construct($cacheDirectory = null)
    {
        $this->cacheDirectory = $cacheDirectory;
    }
    
    /**
     * Load file
     *
     * @param string $file File
     *
     * @return string File contents
     */
    protected abstract function loadFile($file);
    
    /**
     * Load
     *
     * @param string            $file       File
     * @param array|ArrayAccess $parameters Parameters
     */
    public function load($file, &$parameters = array())
    {
        if (null === $this->cacheDirectory) {
            $data = $this->loadFile($file);
        } else {
            $file = new SplFileInfo($file);
            $cacheFile = $this->getCacheFile($file);
            if ($cacheFile->isFile()) {      
                include $cacheFile;
            }
            if (! $cacheFile->isFile() || $ctime != $file->getCtime()) {  
                $data = $data = $this->loadFile($file);
                if (! $cacheFile->getPathInfo()->isDir()) {
                    mkdir($cacheFile->getPathInfo(), 0777, true);
                }
                file_put_contents(
                    $cacheFile,
                    sprintf(
                        '<?php $ctime = %d; $data = %s;',
                        $file->getCtime(),
                        var_export($data, true)
                    )
                );
            }
        }
        foreach ($data as $name => $value) {
            $parameters[$name] = $value;
        }
    }
    
    /**
     * Get cache file
     *
     * @param string $file File
     *
     * @return SplFileInfo Cache file
     */
    private function getCacheFile($file)
    {                
        $cacheFile = sprintf(
            '%s%s%s.php',
            $this->cacheDirectory,
            DIRECTORY_SEPARATOR,
            str_replace(
                DIRECTORY_SEPARATOR,
                '_',
                trim($file, DIRECTORY_SEPARATOR)
            )
        );
        
        return new SplFileInfo($cacheFile);
    }
}