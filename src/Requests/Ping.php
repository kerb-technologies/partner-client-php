<?php

namespace  Kerb\Partner\Requests;

use Kerb\Partner\Request;

class Ping extends Request
{
    public function getPath(): string
    {
        return 'ping';
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
