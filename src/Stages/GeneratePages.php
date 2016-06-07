<?php
namespace loicm\Forests\Stages;

use loicm\Forests\Content\Page;

class GeneratePages
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function __invoke($payload)
    {
        foreach ($payload as $page) {
            $content = new Page($this->config, $page);
            $content->save();
        }

        return $this->config;
    }
}
