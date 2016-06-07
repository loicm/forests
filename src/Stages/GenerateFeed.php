<?php
namespace loicm\Forests\Stages;

use loicm\Forests\Content\Note;

class GenerateFeed
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function __invoke($payload)
    {
        $feed = new \FeedWriter\ATOM();
        $feed->setTitle($this->config->blog_title);
        $feed->setLink($this->config->blog_url . $this->config->base_path . "feed");
        $feed->setDate(new \DateTime());

        $feed->setChannelElement('author', [
            'name' => $this->config->blog_author
        ]);

        $feed->setSelfLink($this->config->blog_url . $this->config->base_path . "feed/");

        $notes = array_slice(array_reverse($payload), 0, 20);

        foreach ($notes as $n) {
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

        mkdir($this->config->output_dir . 'feed');

        file_put_contents(
            $this->config->output_dir . 'feed/index.xml',
            $feed->generateFeed()
        );

        file_put_contents(
            $this->config->output_dir . 'feed/.htaccess',
            "DirectoryIndex index.xml" . "\n".
            "AddType application/atom+xml .xml"
        );

        return $this->config;
    }
}
