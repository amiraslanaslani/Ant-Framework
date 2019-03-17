<?php

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

global $_ANT;

$loader = new \Twig\Loader\FilesystemLoader([
    $_ANT['BASE'] . $_ANT['CONFIG']['paths']['views'],
    $_ANT['BASE'] . $_ANT['CONFIG']['paths']['server_view'],
]);

$twig = new \Twig\Environment($loader, [
    'cache' => $_ANT['BASE'] . $_ANT['CONFIG']['paths']['caches']['templates'],
]);

return $twig;
?>
