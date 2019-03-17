<?php
namespace App\Controller;

use Ant\Abstracts\Controller;
use Amp\Http\Server\Response;
use Amp\Http\Status;

class MainController extends Controller {

    public function main() {
        return view('main.html');
    }
}

?>
