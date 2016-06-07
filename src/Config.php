<?php
namespace loicm\Forests;

class Config
{
    protected $properties;

    /**
     * Create instance of Config
     *
     * @param string $file_path path to config file
     */
    public function __construct($file_path)
    {
        if (!is_readable($file_path)) {
            throw new \Exception("config file not found");
        }

        $this->properties = $this->setDefaults(parse_ini_file($file_path));
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
    protected function setDefaults($config)
    {
        $app_dir = realpath(__DIR__.'/../').'/';

        $config['app_dir'] = $app_dir;

        if (!isset($config['base_path']) || $config['base_path'] == '') {
            $config['base_path'] = '/';
        }
        if (!isset($config['content_dir']) || !is_dir($config['content_dir'])) {
            $config['content_dir'] = $app_dir . 'content/';
        }
        if (!isset($config['output_dir'])) {
            $config['output_dir'] = $app_dir . 'content_html/';
        }
        if (!isset($config['theme']) || $config['theme'] == '') {
            $config['theme'] = 'default';
        }
        $config['themes_dir'] = $app_dir . 'themes/';
        $config['theme_dir'] = $app_dir . 'themes/' . $config['theme'] . '/';
        $config['theme_path'] = $config['base_path'] . 'themes/' . $config['theme'] .'/';

        return $config;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        return false;
    }
}
