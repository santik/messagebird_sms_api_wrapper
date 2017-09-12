<?php

declare(strict_types=1);

namespace Santik\Sms\Application;

final class SmsSender
{
    private $messagesCreator;

    private $client;

    public function __construct(MessagesCreator $messagesCreator, SmsClient $client)
    {
        $this->messagesCreator = $messagesCreator;
        $this->client = $client;
    }

    public function send($data)
    {
        $this->client->send(
            $this->messagesCreator->create($data)
        );
    }
}
