<?php

namespace Kerb\Partner\Exceptions;

class EmptyApiKey extends \Exception
{

    public $message = "No api key provide on request. 
        You can generate on Kerb Partner Dashboard and use on request. Hint: Partner::setApiKey(\$apiKey);";
    public $code = 2;
}
