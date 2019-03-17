<?php
global $_ANT;

$files = glob($_ANT['BASE'] . $_ANT['CONFIG']['paths']['controllers'] . '/*.php');
$instances = [];

foreach ($files as $controller_file) {
    require_once($controller_file);

    $sep = explode($_ANT['CONFIG']['paths']['controllers'], $controller_file);
    $controller_name =  trim(
        explode('.', $sep[1])[0],
        "./"
    );
    $controllers_class_name = str_replace('/','\\','\\' . $_ANT['CONFIG']['paths']['controllers']) . $controller_name;
    $instances[$controller_name] = \App\Controller\MainController::getInstance();
}

return $instances;
?>
