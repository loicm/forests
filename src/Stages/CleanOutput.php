<?php
namespace loicm\Forests\Stages;

use loicm\Forests\Utils;

class CleanOutput
{
    public function __invoke($payload)
    {
        Utils::deleteRecursive($payload->output_dir);

        return $payload;
    }
}
