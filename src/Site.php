<?php
namespace loicm\Forests;

use loicm\Forests\Content\Page;
use loicm\Forests\Content\Note;


class Site
{
    protected $config = null;
    public $pages = array();
    public $notes = array();


    public function __construct($config)
    {
        $this->config = $config;
    }

    public function cleanOutput()
    {
        Utils::deleteRecursive($this->config->output_dir);

        return $this;
    }

    public function listContents($type = '')
    {
        if ($type == '') {
            return $this;
        }

        $this->{$type} = Utils::listRecursive(
            $this->config->content_dir.$type
        );

        return $this;
    }

    public function generateContents($type = '')
    {
        if ($type == '') {
            return $this;
        }

        foreach($this->{$type} as $file) {
            if ($type == 'pages') {
                $content = new Page($this->config, $file);
            } elseif ($type == 'notes') {
                $content = new Note($this->config, $file);
            }
            $content->save();
        }

        return $this;
    }

    public function generateHome()
    {
        $notes_by_year = Notes::groupByYear(array_reverse($this->notes));

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

        return $this;
    }

    public function generateFeed()
    {
        $feed = new \FeedWriter\ATOM();
        $feed->setTitle($this->config->blog_title);
        $feed->setLink($this->config->blog_url.$this->config->base_path."feed");
        $feed->setDate(new \DateTime());

        $feed->setChannelElement(
            'author', array('name' => $this->config->blog_author)
        );

        $feed->setSelfLink($this->config->blog_url.$this->config->base_path."feed/");

        $notes = array_slice(array_reverse($this->notes), 0, 20);

        foreach($notes as $n) {
            $note = new Note($this->config, $n);

            $item = $feed->createNewItem();
            $item->setTitle($note->getTitle());
            $item->setLink($note->getAbsoluteURL());
            $item->setDate($note->getFeedDate());
            $item->setAuthor(
                $this->config->blog_author,
                $this->config->blog_author_email
            );
            $item->setContent(
                preg_replace(
                    '/<img(.+?)src="([^"]*)"/',
                    '<img$1src="'.$this->config->blog_url.$note->getURL().'/$2"',
                    $note->getHtmlContent()
                )
            );

            $feed->addItem($item);
        }

        mkdir($this->config->output_dir.'feed');

        file_put_contents(
            $this->config->output_dir.'feed/index.xml',
            $feed->generateFeed()
        );

        file_put_contents(
            $this->config->output_dir.'feed/.htaccess',
            "DirectoryIndex index.xml"."\n".
            "AddType application/atom+xml .xml"
        );

        return $this;
    }

    public function copyThemeAssets()
    {
        Utils::copyAssets(
            $this->config->theme_dir.'assets',
            $this->config->output_dir.'themes/'.$this->config->theme
        );

        return $this;
    }
}