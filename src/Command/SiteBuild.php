<?php
namespace loicm\Forests\Command;

use loicm\Forests;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class SiteBuild extends Base
{
    protected function configure()
    {
        $this
            ->setName('site:build')
            ->setDescription('Build site')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $site = new Forests\Site($this->app->config);
        $site
            ->cleanOutput()
            ->listContents('pages')
            ->listContents('notes')
            ->generateContents('pages')
            ->generateContents('notes')
            ->generateHome()
            ->generateFeed()
            ->copyThemeAssets()
        ;
    }
}