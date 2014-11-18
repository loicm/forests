<?php
namespace loicm\Forests;


class Config
{
    protected $properties;


    public function __construct($config_file)
    {
        if (!is_readable($config_file)) {
            throw new \Exception("config file not found");
        }

        $app_dir = realpath(__DIR__.'/../').'/';

        $config = parse_ini_file($config_file);

        if (!isset($config['theme']) || $config['theme'] == '') {
            $config['theme'] = 'default';
        }
        if (!isset($config['base_path']) || $config['base_path'] == '') {
            $config['base_path'] = '/';
        }
        if (!isset($config['content_dir']) || !is_dir($config['content_dir'])) {
            echo "content directory not configured","\n";
            exit;
        }
        if (!isset($config['output_dir'])) {
            echo "output directory not configured","\n";
            exit;
        }

        $this->properties = array_merge(
            $config,
            array(
                'app_dir' => $app_dir,
                'content_dir' => $config['content_dir'],
                'output_dir' => $config['output_dir'],
                'themes_dir' => $app_dir.'themes/',
                'theme_dir' => $app_dir.'themes/'.$config['theme'].'/',
                'theme_path' => $config['base_path'].'themes/'.$config['theme'].'/'
            )
        );
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        return false;
    }
}