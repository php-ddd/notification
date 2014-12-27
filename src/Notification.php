<?php
namespace PhpDDD\Notification;

use Exception;

class Notification
{

    /**
     * @var Error[]
     */
    private $errors = array();

    /**
     * @param string    $message
     * @param Exception $exception
     */
    public function addError($message, Exception $exception = null)
    {
        $this->errors[] = new Error($message, $exception);
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @return string[]|null
     */
    public function errorMessages()
    {
        return $this->extractMessage();
    }

    /**
     * @return string|null
     */
    public function firstErrorMessage()
    {
        if (!$this->hasErrors()) {
            return null;
        }
        $error = reset($this->errors);

        return $error->getMessage();
    }

    /**
     * @return string[]
     */
    private function extractMessage()
    {
        return array_map(
            function (Error $error) {
                return $error->getMessage();
            },
            $this->errors
        );
    }
}
