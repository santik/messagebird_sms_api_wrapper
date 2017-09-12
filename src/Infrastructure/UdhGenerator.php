<?php
declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

final class UdhGenerator
{
    public function generate($total, $sequence, $ref): string
    {
        $octet1 = '05';
        $octet2 = '00';
        $octet3 = '03';
        $octet4 = $this->dechexStr($ref);
        $octet5 = $this->dechexStr($total);
        $octet6 = $this->dechexStr($sequence);
        return $octet1 . $octet2 . $octet3 . $octet4 . $octet5 . $octet6;
    }

    private function dechexStr($ref): string
    {
        return ($ref <= 15) ? '0' . dechex($ref) : dechex($ref);
    }
}