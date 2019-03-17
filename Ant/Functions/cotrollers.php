<?php

namespace App\Controller;

use Amp\Http\Server\Response;
use Amp\Http\Status;

function view($file, $input = [], $status = Status::OK) : Response{
    global $_ANT;

    $content = $_ANT['TEMPLATE_ENGINE']->render(
        $file,
        $input
    );

    return new Response(
        $status,
        [
            "content-type" => "text/html; charset=utf-8"
        ],
        $content
    );
}
