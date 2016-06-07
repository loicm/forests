<?php
namespace loicm\Forests\Stages;

use loicm\Forests\Utils;

class CopyThemeAssets
{
    public function __invoke($payload)
    {
        Utils::copyAssets(
            $payload->theme_dir . 'assets',
            $payload->output_dir . 'themes/'. $payload->theme
        );

        return $payload;
    }
}
