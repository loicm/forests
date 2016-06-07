<?php
namespace loicm\Forests\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class SiteDeploy extends Base
{
    protected function configure()
    {
        $this
            ->setName('site:deploy')
            ->setDescription('Deploy site')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        exec('rsync -avz '. $this->app->config->output_dir .' '. $this->app->config->deploy_rsync_dest);
    }
}