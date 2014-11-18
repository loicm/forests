<?php
namespace loicm\Forests;

use Symfony\Component\Console\Application;


class App extends Application
{
    public $config = null;

    public function __construct(Config $config, $name = '', $version = '')
    {
        $this->config = $config;

        if (!is_dir($this->config->content_dir)) {
            echo $this->config->content_dir,"\n";
            echo 'content directory does not exist!'."\n";
            exit;
        }
        if (!is_dir($this->config->output_dir)) {
            echo $this->config->output_dir,"\n";
            echo 'output directory does not exist!'."\n";
            exit;
        }
        if (!is_dir($this->config->theme_dir)) {
            echo 'theme '. $this->config->theme .' does not exist!'."\n";
            exit;
        }

        parent::__construct($name, $version);
    }
}