<?php

namespace loicm\Forests\Stages;

class CopyTalks
{
    public function __invoke($payload)
    {
        if (!is_dir($payload->content_dir . 'presentations')) {
            return;
        }

        exec('cp -r ' .
            $payload->content_dir . 'presentations' .' '.
            $payload->output_dir . 'presentations'
        );

        return $payload;
    }
}
