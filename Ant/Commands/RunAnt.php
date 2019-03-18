<?php
namespace Ant\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ant\AntKernel;

class RunAnt extends Command
{
    protected static $defaultName = 'app:run';
    protected $request_handlers = [];

    protected function configure(){
        $this->request_handlers = [
            new \Ant\RequestHandlers\ControllerRequestHandler,
            new \Ant\RequestHandlers\PublicFileRequestHandler,
            new \Ant\RequestHandlers\PageNotFoundRequestHandler,

        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = new AntKernel();
        foreach($this->request_handlers as $rh)
            $kernel->addRequestHandler($rh);

        $formatter = $this->getHelper('formatter');
        $messages = ['Ant is running...', 'Application is running on: '];
        foreach($kernel->runningOn() as $ro)
            $messages[] = "\t" . $ro;
        $messages[] = '';

        $formattedBlock = $formatter->formatBlock($messages, 'info');
        $output->writeln($formattedBlock);

        $kernel->listen();


    }
}

?>
