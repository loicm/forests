<?php
namespace loicm\Forests\Stages;

use loicm\Forests\Utils;

class ListPages
{
    public function __invoke($payload)
    {
        return Utils::listRecursive($payload->content_dir . 'pages');
    }
}
