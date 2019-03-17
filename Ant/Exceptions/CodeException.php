<?php

namespace Ant\Exceptions;

use Amp\Http\Server\Response;
use Amp\Http\Status;
use Ant\ServerException;

class CodeException extends \Exception implements ServerException {
    protected $code;
    protected $msg;
    protected $template_engine;

    protected $template_file = 'server.error.html';

    public function __construct($msg, $code = 200){
        parent::__construct($msg);

        global $_ANT;

        $this->template_engine = $_ANT['TEMPLATE_ENGINE'];
        $this->msg = $msg;
        $this->code = $code;
    }

    public function setTemplateFile($template_file){
        $this->template_file = $template_file;
    }

    public function getResponse() : Response {
        $output = $this->template_engine->render(
            $this->template_file,
            [
                'error' => [
                    'code' => $this->code,
                    'msg' => $this->msg == null ? Status::getReason($this->code) : $this->msg
                ]
            ]
        );

        return new Response(
            $this->code,
            [
                "content-type" => "text/html; charset=utf-8"
            ],
            $output
        );
    }
}
?>
