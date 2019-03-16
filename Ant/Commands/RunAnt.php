<?php
namespace Ant\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ant\AntKernel;

class RunAnt extends Command
{
    protected static $defaultName = 'app:run';

    protected function configure()
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = new AntKernel();
        $kernel->listen();
    }
}

?>
