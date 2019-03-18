<?php
namespace Ant;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class VarDumper{
    protected static $handler;

    protected static function dump($var, $dumper)
    {
        if (null === self::$handler) {
            $cloner = new VarCloner();
            $dumper =  $dumper;

            self::$handler = function ($var) use ($cloner, $dumper) {
                $dumper->dump($cloner->cloneVar($var));
            };
        }

        return (self::$handler)($var);
    }

    public static function html($var, $cli = false){
        if($cli)
            self::cli($var);
            
        ob_start();
        self::dump(
            $var,
            new HtmlDumper()
        );
        return ob_get_clean();
    }

    public static function cli($var){
        return self::dump(
            $var,
            new CliDumper()
        );
    }

}

?>
