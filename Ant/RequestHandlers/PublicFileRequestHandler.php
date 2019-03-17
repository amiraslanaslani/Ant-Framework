<?php
namespace Ant\RequestHandlers;

use Ant\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;

class PublicFileRequestHandler implements RequestHandler {
    public function __construct(){
    }

    public function detect($url, $method) : ? Response {
        global $_ANT;

        $file = $_ANT['CONFIG']['paths']['public'] . $url;
        if(file_exists($file)){
            $type = \Ant\Functions\find_file_mime_type($file);
            return new Response(Status::OK, [
                "content-type" => "{$type}; charset=utf-8"
            ], file_get_contents($file));
        }
        return null;
    }

}

?>
