<?php
namespace loicm\Forests\Command;

use League\Pipeline\Pipeline;
use loicm\Forests\Stages\CleanOutput;
use loicm\Forests\Stages\ListPages;
use loicm\Forests\Stages\GeneratePages;
use loicm\Forests\Stages\ListNotes;
use loicm\Forests\Stages\GenerateNotes;
use loicm\Forests\Stages\GenerateHome;
use loicm\Forests\Stages\GenerateFeed;
use loicm\Forests\Stages\CopyThemeAssets;
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
        $processPages = (new Pipeline())
            ->pipe(new ListPages())
            ->pipe(new GeneratePages($this->app->config));

        $processNotes = (new Pipeline())
            ->pipe(new ListNotes())
            ->pipe(new GenerateNotes($this->app->config))
            ->pipe(new GenerateHome($this->app->config))
            ->pipe(new GenerateFeed($this->app->config));

        $pipeline = (new Pipeline())
            ->pipe(new CleanOutput())
            ->pipe($processPages)
            ->pipe($processNotes)
            ->pipe(new CopyThemeAssets());

        $pipeline->process($this->app->config);
    }
}