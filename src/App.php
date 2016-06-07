<?php
namespace loicm\Forests;

use Symfony\Component\Console\Application;

class App extends Application
{
    /**
     * @var loicm\Forests\Config
     */
    public $config = null;

    /**
     * Create an App
     *
     * @param loicm\Forests\Config $conf Configuration object
     * @param string $name name of the application
     * @param string $version version of the application
     */
    public function __construct(Config $config, $name = '', $version = '')
    {
        $this->config = $config;

        parent::__construct($name, $version);
    }
}
