<?php
use Symfony\Component\Console\Application;
use Ant\Commands\{
    RunAnt
};

$console = new Application();
$console->add(new RunAnt());
$console->run();
?>
