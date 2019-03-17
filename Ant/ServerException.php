<?php

namespace Ant;

use Amp\Http\Server\Response;

interface ServerException{
    public function getResponse() : Response ;
}

?>
