<?php

declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

use MessageBird\Client;
use MessageBird\Objects\Message as MbMessage;
use Santik\Sms\Application\SmsClient;
use Santik\Sms\Domain\Message;
use Santik\Sms\Domain\ThroughputLimitChecker;

final class MessageBirdBasedSmsClient implements SmsClient
{
    const MAX_BYTE_LENGTH = 153;

    private $client;

    private $limitChecker;


    private $udhGenerator;

    public function __construct(Client $client, ThroughputLimitChecker $limitChecker, UdhGenerator $udhGenerator)
    {
        $this->client = $client;
        $this->limitChecker = $limitChecker;
        $this->udhGenerator = $udhGenerator;
    }

    public function send(Message $message)
    {
        $this->checkThroughput();

        foreach ($this->convertMessage($message) as $message) {
            $this->client->messages->create($message);
        }
    }

    /**
     * @return MbMessage[]
     */
    private function convertSingleMessage(Message $message): array
    {
        $mbMessage = new MbMessage();
        $mbMessage->originator = $message->originator();
        $mbMessage->recipients = [$message->recipient()];
        $mbMessage->body = $message->message();

        return [$mbMessage];
    }

    private function checkThroughput()
    {
        while (!$this->limitChecker->check()) {
            sleep(1);
        }
    }

    /**
     * @return MbMessage[]
     */
    private function convertCombinedMessage(Message $message): array
    {
        $messages = $this->breakMessages($message->message());

        $combinedMessages = [];
        $reference = mt_rand(1,255);
        foreach ($messages as $i => $messagePart) {
            $udh = $this->udhGenerator->generate($reference, count($messages), $i+1);
            $mbMessage = new MbMessage();
            $mbMessage->originator = $message->originator();
            $mbMessage->recipients = [$message->recipient()];
            $mbMessage->setBinarySms($udh, bin2hex($messagePart));
            $combinedMessages[] = $mbMessage;
        }

        return $combinedMessages;
    }

    /**
     * @return MbMessage[]
     */
    private function convertMessage(Message $message): array
    {
        $body = $this->breakMessages($message->message());

        if (count($body) == 1) {
            return $this->convertSingleMessage($message);
        }

        return $this->convertCombinedMessage($message);
    }

    private function breakMessages(string $message): array
    {
        if (strlen($message) <= Message::MAX_MESSAGE_LENGTH) {
            return [$message];
        }

        return str_split($message, self::MAX_BYTE_LENGTH);
    }
}
