<?php
namespace Ant\RequestHandlers;

use Ant\RequestHandler;
use Amp\Http\Server\Response;
use Ant\Exceptions\CodeException;

class ControllerRequestHandler implements RequestHandler {
    public function __construct(){
        $this->controllers = \Ant\Functions\load('controllers');
        $this->router = \Ant\Functions\load('router');
    }

    public function detect($url, $method) : ?Response {
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

    protected function callTarget($target, $params){
        $this->controllers = \Ant\Functions\load('controllers');
        if(is_callable( $target )) {
            return $target();
        }
        else {
            $call = \explode('@', $target);

            if(! isset($this->controllers[$call[0]])){
                throw new CodeException("Controller {$call[0]} is not exists!", 500);
            }

            return \call_user_func_array(
                [
                    $this->controllers[$call[0]], // Controller Instance
                    $call[1] // Method Name
                ],
                $params
            );
        }
    }

    protected function convertControllerResponseToServersOne($response){
        return $response;
    }
}

?>
