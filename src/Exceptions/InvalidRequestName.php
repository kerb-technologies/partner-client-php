<?php

namespace Kerb\Partner\Exceptions;

class InvalidRequestName extends \Exception
{

    public $message = 'Request name not found.';
    public $code = 3;
}
