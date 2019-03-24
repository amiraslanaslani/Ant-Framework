<?php

namespace Ant\Commands;

use Ant\Abstracts\ClassCreator;

class AddController extends ClassCreator
{
    protected function configure()
    {
        $this   ->setFileName("Controller.Template.php")
                ->setClassType("controller")
                ->setDirectory("App/Controllers");

        parent::configure();
    }
}
