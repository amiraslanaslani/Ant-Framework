<?php
namespace Ant\RequestHandlers;

use Ant\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;

class PageNotFoundRequestHandler implements RequestHandler {
    public function __construct(){
    }

    public function detect($url, $method) : ? Response {
        return new Response(Status::OK, [
            "content-type" => "text/plain; charset=utf-8"
        ], "404!");
    }

}

?>
