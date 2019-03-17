<?php

use Ant\Route;
use Amp\Http\Server\Response;
use Amp\Http\Status;

return[
    Route::create('/', "MainController@main"),
    
];
