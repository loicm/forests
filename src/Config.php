<?php

namespace loicm\Forests;

use DotEnv\DotEnv;

class Config
{
    /**
     * Create instance of Config
     *
     * @param string $file_path path to config file
     */
    public function __construct(DotEnv $dotenv)
    {
        $dotenv->load();

        $this->setDefaults();
    }

    /**
     * Check mandatory configuration variables
     */
    public function checkMandatory()
    {
        if (!is_dir($this->content_dir)) {
            echo $this->content_dir,"\n";
            echo 'content directory does not exist!'."\n";
            exit;
        }
        if (!is_dir($this->output_dir)) {
            echo $this->output_dir,"\n";
            echo 'output directory does not exist!'."\n";
            exit;
        }
        if (!is_dir($this->theme_dir)) {
            echo 'theme '. $this->theme .' does not exist!'."\n";
            exit;
        }
    }

    /**
     * Set default properties
     *
     * @param array $config
     * @return array $config
     */
    protected function setDefaults()
    {
        $app_dir = realpath(__DIR__.'/../').'/';

        $this->app_dir = $app_dir;

        if ($this->base_path === false || $this->base_path == '') {
            $this->base_path = '/';
        }
        if ($this->content_dir === false || !is_dir($this->content_dir)) {
            $this->content_dir = $this->app_dir . 'content/';
        }
        if ($this->output_dir === false) {
            $this->output_dir = $this->app_dir . 'content_html/';
        }
        if ($this->theme === false || $this->theme == '') {
            $this->theme = 'plain';
        }
        $this->themes_dir = $this->app_dir . 'themes/';
        $this->theme_dir = $this->app_dir . 'themes/' . $this->theme . '/';
        $this->theme_path = $this->base_path . 'themes/' . $this->theme .'/';
    }

    public function __get($name)
    {
        return getenv(strtoupper($name));
    }
}
