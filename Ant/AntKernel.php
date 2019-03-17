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
        $this->router = require('Loads/router.php');

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
        $match = $this->router->match($url, $method);
        // \var_dump($match);
        if( is_array($match) && is_callable( $match['target'] ) ) {
            $response = $match['target']();
        	$response = $this->convertControllerResponseToServersOne(
                $response
            );
            return $response;
        }
        echo 1;

        return new Response(Status::OK, [
            "content-type" => "text/plain; charset=utf-8"
        ], "404!");
    }

    private function convertControllerResponseToServersOne($response){
        return $response;
    }

}

?>
