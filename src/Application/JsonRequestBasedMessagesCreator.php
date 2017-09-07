<?php

declare(strict_types=1);

namespace Santik\Sms\Application;

use Santik\Sms\Domain\Message;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

class JsonRequestBasedMessagesCreator implements MessagesCreator
{
    /**
     * @param Request $data
     * @return Message[]
     */
    public function create($data): array
    {
        $this->guardData($data);

        $data = json_decode($data->getContent(), true);

        $messageParts = $this->breakMessages($data['message']);

        $messages = [];

        foreach ($messageParts as $messagePart) {
            $messages[] = new Message($data['recipient'], $data['originator'], $messagePart);
        }

        return $messages;
    }

    /**
     * @param Request $data
     */
    private function guardData($data)
    {
        Assert::isInstanceOf($data, Request::class);

        $data = $data->getContent();
        Assert::notEmpty($data, 'data should be json inside "data" parameter');

        $data = json_decode($data, true);
        Assert::notEmpty($data, 'data should be json inside "data" parameter');

        Assert::keyExists($data, 'recipient');
        Assert::keyExists($data, 'originator');
        Assert::keyExists($data, 'message');
    }

    private function breakMessages(string $message): array
    {
        return str_split($message, Message::MAX_MESSAGE_LENGTH);
    }
}