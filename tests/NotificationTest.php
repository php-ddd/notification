<?php
namespace PhpDDD\Notification;

use PHPUnit_Framework_TestCase;

class NotificationTest extends PHPUnit_Framework_TestCase
{
    public function testNoErrorMessage()
    {
        $notification = new Notification();

        $this->assertFalse($notification->hasErrors());
        $this->assertEquals(array(), $notification->errorMessages());
        $this->assertNull($notification->firstErrorMessage());
    }

    public function testOneErrorMessage()
    {
        $errorMessage = 'my error';
        $notification = new Notification();
        $notification->addError($errorMessage);

        $this->assertTrue($notification->hasErrors());
        $this->assertEquals(array($errorMessage), $notification->errorMessages());
        $this->assertEquals($errorMessage, $notification->firstErrorMessage());
    }

    public function testMultipleErrorMessage()
    {
        $errorMessage  = 'my error 1';
        $errorMessage2 = 'my error 2';
        $notification  = new Notification();
        $notification->addError($errorMessage);
        $notification->addError($errorMessage2);

        $this->assertTrue($notification->hasErrors());
        $this->assertEquals(array($errorMessage,$errorMessage2), $notification->errorMessages());
        $this->assertEquals($errorMessage, $notification->firstErrorMessage());
    }
}
