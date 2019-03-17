<?php
namespace Ant;

spl_autoload_register(function($class_name){
    include(str_replace('\\','/',$class_name) . '.php');
});

$_ANT['CONFIG'] = require('config.php');
$_ANT['BASE'] = __DIR__ . '/../';
?>
