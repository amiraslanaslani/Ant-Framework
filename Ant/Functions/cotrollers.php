<?php

namespace App\Controller;

use Amp\Http\Server\Response;
use Amp\Http\Status;
use Ant\MiddleResponse;
use Ant\VarDumper;

function dump_html($var){
    return html(
        VarDumper::html($var)
    );
}

function view($file, $input = [], $status = Status::OK) : MiddleResponse {
    global $_ANT;

    $content = $_ANT['TEMPLATE_ENGINE']->render(
        $file,
        $input
    );

    $response = MiddleResponse::create(
        $status,
        [
            "content-type" => "text/html; charset=utf-8"
        ],
        $content
    );

    return $response;
}

function json($data, $status = Status::OK) : MiddleResponse {
    return MiddleResponse::create(
        $status,
        [
            "content-type" => "application/json; charset=utf-8"
        ],
        \json_encode($data)
    );
}

function text($data, $status = Status::OK) : MiddleResponse {
    return MiddleResponse::create(
        $status,
        [
            "content-type" => "text/plain; charset=utf-8"
        ],
        $data
    );
}

function html($data, $status = Status::OK) : MiddleResponse {
    return MiddleResponse::create(
        $status,
        [
            "content-type" => "text/html; charset=utf-8"
        ],
        $data
    );
}

function redirect($location, $permanent = false) : MiddleResponse {
    return MiddleResponse::create(
        $permanent ? (Status::MOVED_PERMANENTLY) : (Status::FOUND),
        [
            "content-type" => "text/plain; charset=utf-8",
            "Location" => $location
        ],
        $data
    );
}
