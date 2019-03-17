<?php

namespace Ant\Server;

use Amp\Http\Status;
use Amp\Promise;
use Amp\Success;
use Amp\Http\Server\ErrorHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Request;

/**
 * ErrorHandler instance used by default if none is given.
 */
final class AntErrorHandler implements ErrorHandler {
    protected $template_engine;

    public function __construct(){
        global $_ANT;
        $this->template_engine = $_ANT['TEMPLATE_ENGINE'];
    }

    /** @var string[] */
    private $cache = [];

    /** {@inheritdoc} */
    public function handleError(int $statusCode, string $reason = null, Request $request = null): Promise {

        $output = $this->template_engine->render('server.error.html', [
            'error' => [
                'code' => $statusCode,
                'msg' => Status::getReason($statusCode)
            ]
        ]);

        $response = new Response(
            $statusCode,
            [
                "content-type" => "text/html; charset=utf-8"
            ],
            $output
        );

        $response->setStatus($statusCode, $reason);

        return new Success($response);
    }
}
