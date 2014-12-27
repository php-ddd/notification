<?php
namespace PhpDDD\Notification;

/**
 * This class aims to stores all the validation errors you will find.
 */
class Notification
{

    /**
     * @var Error[]
     */
    private $errors = array();

    /**
     * @param string $message
     */
    public function addError($message)
    {
        $this->errors[] = new Error($message);
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @return string[]
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
            return;
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
