<?php

use Ant\Route;
use Amp\Http\Server\Response;
use Amp\Http\Status;

return[
    Route::create('/', "MainController@main")->name('main_page'),

    Route::create('/hi', function(){
        // echo 'Page: /hi ;';
        return new Response(Status::OK, [
            "content-type" => "text/plain; charset=utf-8"
        ], "Hi to every body!");
    }),
];
