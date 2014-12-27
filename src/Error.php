<?php
namespace PhpDDD\Notification;

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
     * @param string         $message
     */
    public function __construct($message)
    {
        if (!is_string($message)) {
            throw new InvalidArgumentException('The first parameter of Error must be a string.');
        }
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
