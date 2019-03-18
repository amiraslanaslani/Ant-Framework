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

    protected $request_handlers = array();

    public function addRequestHandler(RequestHandler $rh){
        $this->request_handlers[] = $rh;
    }

    public function listen(){

        \Amp\Loop::run(function () {
            global $_ANT;
            $sockets = [];

            foreach($_ANT['CONFIG']['server']['listen'] as $ips){
                $sockets[] = Socket\listen($ips);
            }

            $server = new Server($sockets, new CallableRequestHandler(
                function(Request $request){
                    return $this->handleRequest($request);
                }),
                new NullLogger
            );

            $server->setErrorHandler(
                new \Ant\Server\AntErrorHandler()
            );

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
        try{
            foreach ($this->request_handlers as $key => $request_handler) {
                $response = $request_handler->detect($url, $method);
                if($response != null)
                    return $response;
            }
        }
        catch(ServerException $e){
            return $e->getResponse();
        }
        catch(\Exception $e){
            echo $e->getTraceAsString();
        }
    }

    public function runningOn(){
        global $_ANT;
        return $_ANT['CONFIG']['server']['listen'];
    }

}

?>
