<?php

namespace Santik\Sms\Application;

use Santik\Sms\Domain\Message;

interface MessagesCreator
{
    public function create($data): Message;
}
