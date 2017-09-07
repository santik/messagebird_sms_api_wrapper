<?php

namespace Santik\Sms\Infrastructure;

use Santik\Sms\Domain\Message;

interface SmsClient
{
    public function send(Message $data);
}