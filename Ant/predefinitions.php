<?php
namespace Ant;

// Add Classs Autoload
spl_autoload_register(function($class_name){
    include(str_replace('\\','/',$class_name) . '.php');
});

// Set Constants
$_ANT['CONFIG'] = require('config.php');
$_ANT['BASE'] = __DIR__ . '/../';

// Load Functions
$files = glob($_ANT['BASE'] . $_ANT['CONFIG']['paths']['functions'] . '/*.php');
foreach ($files as $file) {
    require_once($file);
}

?>
