<?php

declare(strict_types=1);

namespace Santik\Sms\Application;

use Santik\Sms\Domain\Message;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class JsonRequestBasedMessagesCreator implements MessagesCreator
{
    /**
     * @param Request $data
     */
    public function create($data): Message
    {
        $this->guardData($data);

        $data = json_decode($data->getContent(), true);

        return new Message($data['recipient'], $data['originator'], $data['message']);
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
}
