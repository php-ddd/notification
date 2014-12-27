<?php
namespace PhpDDD\Notification;

use Exception;

/**
 * This class is for internal purpose only
 */
class Error
{

    /**
     * @var string
     */
    private $message;

    /**
     * @var Exception
     */
    private $cause;

    /**
     * @param string         $message
     * @param Exception|null $cause
     */
    public function __construct($message, Exception $cause = null)
    {
        if (!is_string($message)) {
            throw new InvalidArgumentException('The first parameter of Error must be a string.');
        }
        $this->message = $message;
        $this->cause   = $cause;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
