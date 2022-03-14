<?php

namespace ShockingHuman\ServerTools\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ShockingHuman\ServerTools\Operations\Config;
use ShockingHuman\ServerTools\Operations\Initialize as Init;

class Initialize extends Command
{
    protected static $defaultDescription = "Initialize PingNet";

    protected function configure()
    {
        $this->addArgument('username', InputArgument::OPTIONAL, "Name of current user")
        ->addOption('reverse', null, InputOption::VALUE_NONE, "Reverse previous initialization");
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('reverse')){
            unlink(Config::return('url_list'));
            unlink(__DIR__.'/../../pingnet.service');
            $output->writeln('Initialization Reversed');
            return Command::SUCCESS;
        }
        if
        (
            file_exists(Config::return('url_list')) and
            file_exists(__DIR__.'/../../pingnet.service')
        )
        {
            $output->writeln('Already Initialized');
            return Command::SUCCESS;
        }
        Init::pingnet();
        return Command::SUCCESS;
    }
}