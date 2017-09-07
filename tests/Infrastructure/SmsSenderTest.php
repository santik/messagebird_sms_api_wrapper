<?php

declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

use PHPUnit\Framework\TestCase;
use Santik\Sms\Application\MessagesCreator;
use Santik\Sms\Domain\Message;

class SmsSenderTest extends TestCase
{
    public function testSend_WithCorrectParameters_ShouldCallMethodsFromDependencies()
    {
        $data = 'some data';

        $domainMessages = [
            new Message('some', 'another', 'foo'),
            new Message('some', 'another', 'foo'),
            new Message('some', 'another', 'foo'),
        ];

        $messagesCreator = $this->prophesize(MessagesCreator::class);
        $messagesCreator->create($data)->willReturn($domainMessages);

        $client = $this->prophesize(SmsSenderClient::class);
        foreach ($domainMessages as $domainMessage) {
            $client->send($domainMessage)->shouldBeCalled();
        }

        $sender = new SmsSender($messagesCreator->reveal(), $client->reveal());

        $sender->send($data);
    }
}
