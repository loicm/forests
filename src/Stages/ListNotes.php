<?php
namespace loicm\Forests\Stages;

use loicm\Forests\Utils;

class ListNotes
{
    public function __invoke($payload)
    {
        return Utils::listRecursive($payload->content_dir . 'notes');
    }
}
