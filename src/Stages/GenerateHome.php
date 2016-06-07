<?php
namespace loicm\Forests\Stages;

use loicm\Forests\Content\Note;
use loicm\Forests\Notes;

class GenerateHome
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function __invoke($payload)
    {
        $notes_by_year = Notes::groupByYear(array_reverse($payload));

        $home_content = '';

        foreach($notes_by_year as $year => $notes) {
            $home_content.= '# Notes en '.$year."\n";

            foreach($notes as $note) {
                $n = new Note($this->config, $note);

                $home_content.= sprintf(
                    '- [%s](%s) − %s'."\n",
                    $n->getTitle(),
                    $n->getURL(),
                    $n->getDate()
                );
            }
            $home_content.= "\n\n";
        }

        $parser = new \cebe\markdown\MarkdownExtra();
        $parser->html5 = true;
        $home_content = $parser->parse($home_content);

        $loader = new \Twig_Loader_Filesystem($this->config->theme_dir);
        $twig = new \Twig_Environment($loader, [
            'autoescape' => false
        ]);

        file_put_contents(
            $this->config->output_dir.'index.html',
            $twig->render(
                'home.html',
                [
                    'page' => [
                        'content' => $home_content,
                        'name' => 'home',
                        'title' => 'Accueil'
                    ],
                    'blog' => [
                        'title' => $this->config->blog_title,
                        'base_path' => $this->config->base_path,
                        'theme_path' => $this->config->theme_path
                    ]
                ]
            )
        );

        return $payload;
    }
}
