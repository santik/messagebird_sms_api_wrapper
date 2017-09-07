<?php

declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

use Santik\Sms\Application\MessagesCreator;

class SmsSender
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
        $messages = $this->messagesCreator->create($data);

        foreach ($messages as $message) {
            $this->client->send($message);
        }
    }
}
