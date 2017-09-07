<?php

declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

use MessageBird\Client;
use MessageBird\Resources\Messages;
use PHPUnit\Framework\TestCase;
use Santik\Sms\Domain\Message;
use MessageBird\Objects\Message as MbMessage;

class MessageBirdBasedSenderClientTest extends TestCase
{
    public function testSend_WithCorrectParameters_ShouldCallClientSendMethod()
    {
        $recipient = 'recipient';
        $originator = 'originator';
        $smsBody = 'message';

        $domainMessage = new Message($recipient, $originator, $smsBody);

        $mbMessage = new MbMessage();
        $mbMessage->recipients = [$recipient];
        $mbMessage->originator = $originator;
        $mbMessage->body = $smsBody;

        $messages = $this->prophesize(Messages::class);
        $messages->create($mbMessage)->shouldBeCalled();

        $client = $this->prophesize(Client::class);
        $client->messages = $messages->reveal();

        $client = new MessageBirdBasedSmsClient($client->reveal());
        $client->send($domainMessage);
    }
}
