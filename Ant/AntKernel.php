<?php

namespace Ant;

use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Server;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Amp\Socket;
use Psr\Log\NullLogger;

class AntKernel {

    public function __construct(){
        global $_ANT;
        $this->controllers = Functions\load('controllers');
        $this->router = Functions\load('router');
    }

    public function listen(){
        global $_ANT;

        \Amp\Loop::run(function () {
            global $_ANT;
            $sockets = [];

            foreach($_ANT['CONFIG']['server']['listen'] as $ips){
                $sockets[] = Socket\listen($ips);
            }

            $server = new Server($sockets, new CallableRequestHandler(function(Request $request){
                return $this->handleRequest($request);
            }), new NullLogger);

            yield $server->start();

            \Amp\Loop::onSignal(SIGINT, function (string $watcherId) use ($server) {
                \Amp\Loop::cancel($watcherId);
                yield $server->stop();
            });
        });
    }

    private function handleRequest(Request $request) : Response {
        $method = $request->getMethod();
        $url = $request->getUri()->getPath();

        // Detect Controller
        $controller = $this->detectController($url, $method);
        if($controller != null)
            return $controller;

        // Detect Public File
        $public_file = $this->detectPublicFile($url, $method);
        if($public_file != null)
            return $public_file;

        // Detect 404 Error!
        return new Response(Status::OK, [
            "content-type" => "text/plain; charset=utf-8"
        ], "404!");
    }

    private function detectController($url, $method){
        $match = $this->router->match($url, $method);

        if( is_array($match) ) {
            $response = $this->callTarget($match['target'], $match['params']);
        	$response = $this->convertControllerResponseToServersOne(
                $response
            );
            return $response;
        }
        return null;
    }

    private function detectPublicFile($url, $method){
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

    private function callTarget($target, $params){
        if(is_callable( $target )) {
            return $target();
        }
        else {
            $call = \explode('@', $target);
            return \call_user_func_array(
                [
                    $this->controllers[$call[0]], // Controller Instance
                    $call[1] // Method Name
                ],
                $params
            );
        }
    }

    private function convertControllerResponseToServersOne($response){
        return $response;
    }
}

?>
