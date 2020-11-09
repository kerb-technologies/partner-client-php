<?php

namespace Kerb\Partner\Exceptions;

class RotatedApiKey extends \Exception
{

    private $templateMessage = "Your api key has been rotated (expire at %s).
        This exception thrown due to expire api key (rotated) and your application set Partner::\$thrownAtRotateKey as true,
 set that variable as false if you wanna ignore it.
        ";

    public $code = 4;

    public function __construct($timestamp)
    {
        $date = new \Datetime();
        $date->setTimestamp($timestamp);
        $date->setTimeZone(new \DateTimeZone('UTC'));
        $message = sprintf($this->templateMessage, $date->format('c'));
        parent::__construct($message, $this->code);
    }
}
