<?php

declare(strict_types=1);

namespace Santik\Sms\Domain;

final class Message
{
    const MAX_MESSAGE_LENGTH = 160;

    private $recipient;

    private $originator;

    private $message;

    public function __construct(string $recipient, string $originator, string $message)
    {
        $this->recipient = $recipient;
        $this->originator = $originator;
        $this->message = $message;
    }

    public function recipient(): string
    {
        return $this->recipient;
    }

    public function originator(): string
    {
        return $this->originator;
    }

    public function message(): string
    {
        return $this->message;
    }
}
