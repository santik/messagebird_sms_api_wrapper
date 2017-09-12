<?php

declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

use MessageBird\Client;
use MessageBird\Resources\Messages;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Santik\Sms\Domain\Message;
use MessageBird\Objects\Message as MbMessage;
use Santik\Sms\Domain\ThroughputLimitChecker;

final class MessageBirdBasedSenderClientTest extends TestCase
{
    public function testSend_WithCorrectParametersShortMessage_ShouldCallClientSendMethod()
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

        $limitChecker = $this->prophesize(ThroughputLimitChecker::class);
        $limitChecker->check()->willReturn(true);

        $client = new MessageBirdBasedSmsClient($client->reveal(), $limitChecker->reveal(), new UdhGenerator());
        $client->send($domainMessage);
    }

    public function testSend_WithCorrectParametersCombined_ShouldCallClientSendMethod()
    {
        $recipient = 'recipient';
        $originator = 'originator';
        $smsBody =  'very long message very long message very long message very long message very long message very long message very long message very long message very long message very long message very long message';

        $udhGenerator = $this->prophesize(UdhGenerator::class);
        $udh = 'someudh';
        $udhGenerator->generate(Argument::any(), Argument::any(), Argument::any())->willReturn('someudh');

        $domainMessage = new Message($recipient, $originator, $smsBody);

        $mbMessages = [];
        foreach (str_split($smsBody, MessageBirdBasedSmsClient::MAX_BYTE_LENGTH) as $i => $messagePart) {
            $mbMessage = new MbMessage();
            $mbMessage->recipients = [$recipient];
            $mbMessage->originator = $originator;
            $mbMessage->setBinarySms($udh, bin2hex($messagePart));
            $mbMessages[] = $mbMessage;
        }


        $messages = $this->prophesize(Messages::class);

        foreach ($mbMessages as $mbMessage) {
            $messages->create($mbMessage)->shouldBeCalled();
        }

        $client = $this->prophesize(Client::class);
        $client->messages = $messages->reveal();

        $limitChecker = $this->prophesize(ThroughputLimitChecker::class);
        $limitChecker->check()->willReturn(true);

        $client = new MessageBirdBasedSmsClient($client->reveal(), $limitChecker->reveal(), $udhGenerator->reveal());
        $client->send($domainMessage);
    }
}
