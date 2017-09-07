<?php

declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

use MessageBird\Client;
use MessageBird\Objects\Message as MbMessage;
use Santik\Sms\Domain\Message;

class MessageBirdBasedSmsClient implements SmsClient
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function send(Message $message)
    {
        $mbMessage = new MbMessage();
        $mbMessage->originator = $message->originator();
        $mbMessage->recipients = [$message->recipient()];
        $mbMessage->body = $message->message();

        $this->client->messages->create($mbMessage);
    }
}
