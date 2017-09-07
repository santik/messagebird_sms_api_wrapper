<?php

declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

use MessageBird\Client;
use MessageBird\Objects\Message as MbMessage;
use Santik\Sms\Domain\Message;
use Santik\Sms\Domain\ThroughputLimitChecker;

final class MessageBirdBasedSmsClient implements SmsClient
{
    private $client;

    private $limitChecker;

    public function __construct(Client $client, ThroughputLimitChecker $limitChecker)
    {
        $this->client = $client;
        $this->limitChecker = $limitChecker;
    }

    public function send(Message $message)
    {
        $this->checkThroughput();

        $this->client->messages->create(
            $this->convertMessage($message)
        );
    }

    private function convertMessage(Message $message): MbMessage
    {
        $mbMessage = new MbMessage();
        $mbMessage->originator = $message->originator();
        $mbMessage->recipients = [$message->recipient()];
        $mbMessage->body = $message->message();

        return $mbMessage;
    }

    private function checkThroughput()
    {
        while (!$this->limitChecker->check()) {
            sleep(1);
        }
    }
}
