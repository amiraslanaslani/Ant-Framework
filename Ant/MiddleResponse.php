<?php

namespace Ant;

use Amp\Http\Server\Response;
use Amp\Http\Cookie\CookieAttributes;
use Amp\Http\Cookie\ResponseCookie;

class MiddleResponse {
    protected $response;
    protected $cookies = [];

    public static function create(int $code = 200, array $headers = [], $stringOrStream = null){
        return new self(
            new Response(
                $code,
                $headers,
                $stringOrStream
            )
        );
    }

    public function __construct(Response $response){
        $this->response = $response;
    }

    public function addCookie($name, $value, CookieAttributes $attr = null){
        $cookies[] = [$name, $value, $attr];
    }

    public function getResponse() : Response {
        foreach($this->cookies as $cookie){
            $this->response->setCookie(
                new ResponseCookie($cookie[0], $cookie[1], $cookie[2])
            );
        }

        return $this->response;
    }
}

?>
