<?php
namespace Ant\RequestHandlers;

use Ant\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;

class PageNotFoundRequestHandler implements RequestHandler {
    public function __construct(){
    }

    public function detect($url, $method, $request) : ? Response {
        throw new \Ant\Exceptions\CodeException("Page not found!", 404);
    }

}

?>
