<?php
use Symfony\Component\Console\Application;
use Ant\Commands\{
    RunAnt,
    AddController
};

$console = new Application();
$console->add(new RunAnt());
$console->add(new AddController());
$console->run();
?>
