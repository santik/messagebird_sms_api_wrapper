<?php

namespace Santik\Sms\Domain;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class MessageTest extends TestCase
{
    public function testCreate_withCorrectParameters_ShouldReturnMessage()
    {
        $recipient = 'recipient';
        $originator = 'originator';
        $message = 'message';

        $object = new Message($recipient, $originator, $message);

        $this->assertEquals($recipient, $object->recipient());
        $this->assertEquals($originator, $object->originator());
        $this->assertEquals($message, $object->message());
    }

    public function testCreate_withLongMessage_ShouldThrowException()
    {
        $recipient = 'recipient';
        $originator = 'originator';
        $message = 'message message message message message message message message message message message message message message message message message message message message message message message message message message message message message message message message message message message ';

        $this->expectException(\InvalidArgumentException::class);

        $object = new Message($recipient, $originator, $message);
    }
}
