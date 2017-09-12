<?php

declare(strict_types=1);

namespace Santik\Sms\Application;

use PHPUnit\Framework\TestCase;
use Santik\Sms\Domain\Message;

final class SmsSenderTest extends TestCase
{
    public function testSend_WithCorrectParameters_ShouldCallMethodsFromDependencies()
    {
        $data = 'some data';

        $domainMessage = new Message('some', 'another', 'message');

        $messagesCreator = $this->prophesize(MessagesCreator::class);
        $messagesCreator->create($data)->willReturn($domainMessage);

        $client = $this->prophesize(SmsClient::class);
        $client->send($domainMessage)->shouldBeCalled();

        $sender = new SmsSender($messagesCreator->reveal(), $client->reveal());

        $sender->send($data);
    }
}
