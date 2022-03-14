<?php

namespace ShockingHuman\ServerTools\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ShockingHuman\ServerTools\Operations\Config;

class SystemD extends Command
{
    protected static $defaultDescription = 'Perform system operations';

    protected function configure()
    {
        $this->addArgument('operation', InputArgument::OPTIONAL, 'status, start, restart, stop')->addUsage('status, start, restart, stop');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $op = $input->getArgument('operation');
        system("systemctl $op pingnet");
        return Command::SUCCESS;
    }
}