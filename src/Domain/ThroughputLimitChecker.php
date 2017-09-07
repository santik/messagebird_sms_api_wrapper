<?php

namespace Santik\Sms\Domain;

interface ThroughputLimitChecker
{
    public function check(): bool;
}
