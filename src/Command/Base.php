<?php
namespace loicm\Forests\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Base extends Command
{
    public function __construct($app)
    {
        $this->app = $app;
        parent::__construct();
    }
}