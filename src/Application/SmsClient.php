<?php

namespace Santik\Sms\Application;

use Santik\Sms\Domain\Message;

interface SmsClient
{
    public function send(Message $data);
}
