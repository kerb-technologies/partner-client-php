<?php

namespace Kerb\Partner\Exceptions;

class EmptyApiHost extends \Exception
{

    public $message = "No api host provide on request.
        Api host need to be set as environment variable with name KERB_PARTNER_HOST.";

    public $code = 1;
}
