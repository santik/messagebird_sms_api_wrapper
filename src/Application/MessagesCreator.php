<?php

namespace Santik\Sms\Application;

use Santik\Sms\Domain\Message;

interface MessagesCreator
{
    /**
     * @return Message[]
     */
    public function create($data): array;
}
