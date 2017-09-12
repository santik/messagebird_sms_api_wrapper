<?php

declare(strict_types=1);

namespace Santik\Sms\Domain;

use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
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
}
