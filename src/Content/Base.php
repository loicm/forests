<?php
namespace loicm\Forests\Content;

use loicm\Forests;
use loicm\Forests\Config;


class Base
{
    protected $config = null;
    protected $metadata = null;
    protected $source_file = '';
    protected $source_content = '';
    protected $source_content_only = '';
    protected $html_content = '';
    protected $output_content_dir = '';
    protected $tpl_vars = [];


    public function __construct(Config $config, $source_file)
    {
        $this->config = $config;

        if (!is_readable($source_file)) {
            throw new \Exception(ucfirst($this->type).' not found ('. $source_file .')');
        }

        $this->source_file = $source_file;
        $this->source_content = file_get_contents($source_file);
        $this->metadata = new Forests\ContentMetaData($this->source_content);
        $this->source_content_only = Forests\Utils::getContentOnly($this->source_content);
        $parser = new \cebe\markdown\MarkdownExtra();
        $parser->html5 = true;
        $this->html_content = $parser->parse($this->source_content_only);

        $this->tpl_vars = [
            'page' => [
                'content' => $this->html_content,
                'title' => $this->metadata->title,
            ]
        ];
    }


    public function save()
    {
        mkdir($this->output_content_dir, 0755, true);

        $loader = new \Twig_Loader_Filesystem($this->config->theme_dir);
        $twig = new \Twig_Environment($loader, [
            'autoescape' => false
        ]);

        file_put_contents(
            $this->output_file,
            $twig->render(
                $this->type.'.html',
                array_merge(
                    [
                        'blog' => [
                            'title' => $this->config->blog_title,
                            'url' => $this->config->blog_url,
                            'author' => $this->config->blog_author,
                            'base_path' => $this->config->base_path,
                            'theme_path' => $this->config->theme_path
                        ]
                    ],
                    $this->tpl_vars
                )
            )
        );

        $path_parts = pathinfo($this->source_file);

        Forests\Utils::copyAssets(
            $path_parts['dirname'],
            $this->output_content_dir
        );

        return $this;
    }

    public function getHtmlContent()
    {
        return $this->html_content;
    }
}