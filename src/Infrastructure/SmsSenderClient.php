<?php

namespace Santik\Sms\Infrastructure;

use Santik\Sms\Domain\Message;

interface SmsSenderClient
{
    public function send(Message $data);
}