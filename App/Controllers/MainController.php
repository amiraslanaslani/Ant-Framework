<?php
namespace App\Controller;

use Ant\Abstracts\Controller;
use Amp\Http\Server\Response;
use Amp\Http\Status;

class MainController extends Controller {

    public function __construct(){
        // echo 'MainController is constructed!';
    }

    public function main() {
        return new Response(Status::OK, [
            "content-type" => "text/plain; charset=utf-8"
        ], "Here is main page!");
    }
}

?>
