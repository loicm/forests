<?php
namespace loicm\Forests\Content;

use loicm\Forests\Config;


class Page extends Base
{
    protected $type = 'page';


    public function __construct(Config $config, $page_file)
    {
        parent::__construct($config, $page_file);

        $path_parts = pathinfo($this->source_file);
        $this->output_content_dir = $this->config->output_dir.$path_parts['filename'];
        $this->output_file = $this->output_content_dir.'/index.html';
    }
}