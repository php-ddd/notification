<?php
namespace PhpDDD\Notification;

use Exception;
use PHPUnit_Framework_TestCase;

class ErrorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIntegerThrowException()
    {
        new Error(100);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionThrowException()
    {
        new Error(new Exception());
    }

    public function testValidMessage()
    {
        $errorMessage = 'This is an error message';
        $error        = new Error($errorMessage);
        $this->assertEquals($errorMessage, $error->getMessage());
    }
}
