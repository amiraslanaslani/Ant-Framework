<?php

namespace Ant;

use Amp\Http\Server\Response;

interface RequestHandler{
    public function detect($url, $method) : ? Response ;
}
