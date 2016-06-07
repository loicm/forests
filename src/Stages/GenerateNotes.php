<?php
namespace loicm\Forests\Stages;

use loicm\Forests\Content\Note;

class GenerateNotes
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function __invoke($payload)
    {
        foreach ($payload as $note) {
            $content = new Note($this->config, $note);
            $content->save();
        }

        return $payload;
    }
}
