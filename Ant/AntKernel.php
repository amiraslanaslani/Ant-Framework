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
        global $_ANT;
        $method = $request->getMethod();
        $url = $request->getUri()->getPath();

        // Detect Controller
        $match = $this->router->match($url, $method);
        if( is_array($match) && is_callable( $match['target'] ) ) {
            $response = $match['target']();
        	$response = $this->convertControllerResponseToServersOne(
                $response
            );
            return $response;
        }

        // Detect Public File
        $file = $_ANT['CONFIG']['paths']['public'] . $url;
        if(file_exists($file)){
            $type = $this->find_file_mime_type($file);
            return new Response(Status::OK, [
                "content-type" => "{$type}; charset=utf-8"
            ], file_get_contents($file));
        }

        // Detect 404 Error!
        return new Response(Status::OK, [
            "content-type" => "text/plain; charset=utf-8"
        ], "404!");
    }

    private function convertControllerResponseToServersOne($response){
        return $response;
    }

    private function find_file_mime_type($filename){
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $value = explode(".", $filename);
        $ext = strtolower(array_pop($value));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return mime_content_type($filename);
        }
    }

}

?>
