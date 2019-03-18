<?php
namespace Ant\RequestHandlers;

use Ant\RequestHandler;
use Amp\Http\Server\Response;
use Ant\Exceptions\CodeException;
use Ant\MiddleResponse;
use Amp\Http\Server\Request;
use Ant\InputAllocator\InputAllocator;
use Ant\InputAllocator\Input;

class ControllerRequestHandler implements RequestHandler {
    public function __construct(){
        $this->controllers = \Ant\Functions\load('controllers');
        $this->router = \Ant\Functions\load('router');
    }

    public function detect($url, $method, $request) : ? Response {
        $match = $this->router->match($url, $method);
        if( is_array($match) ) {
            $response = $this->callTarget($match['target'], $match['params'], $request);
            $response = $this->convertControllerResponseToServersOne(
                $response
            );
            return $response;
        }
        return null;
    }

    protected function callTarget($target, $params, Request $request){
        $this->controllers = \Ant\Functions\load('controllers');
        if(is_callable( $target )) {
            return $target();
        }
        else {
            $call = \explode('@', $target);

            if(! isset($this->controllers[$call[0]])){
                throw new CodeException("Controller {$call[0]} is not exists!", 500);
            }

            $input_alloc = new InputAllocator();
            $input_alloc->setMethod(
                get_class($this->controllers[$call[0]]),
                $call[1]
            );
            $input_alloc->parseArray($params);

            $request_input = new Input($request);
            $request_input->addBound(Input::TYPE, 'Amp\Http\Server\Request');

            $input_alloc->addInput($request_input);
            $params = $input_alloc->getParameters();

            $result = \call_user_func_array(
                [
                    $this->controllers[$call[0]], // Controller Instance
                    $call[1] // Method Name
                ],
                $params
            );

            if($result == null){
                throw new CodeException("Method {$call[1]} is not exists!", 500);
            }

            return $result;
        }
    }

    protected function convertControllerResponseToServersOne($response) : Response {
        if($response instanceof Response)
            return $response;

        if($response instanceof MiddleResponse)
            return $response->getResponse();

        if(\is_array($response))
            return new Response(
                200,
                [
                    "content-type" => "application/json; charset=utf-8"
                ],
                \json_encode($response)
            );

        if(\is_string($response))
            return new Response(
                200,
                [
                    "content-type" => "text/plain; charset=utf-8"
                ],
                $response
            );

    }
}

?>
