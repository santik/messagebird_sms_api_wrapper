<?php

declare(strict_types=1);

namespace Santik\Sms\Application;

use Santik\Sms\Domain\ThroughputLimitChecker;

final class FileBasedThroughputLimitChecker implements ThroughputLimitChecker
{
    private $filePath;

    private $limit;

    public function __construct(string $filePath, int $limit)
    {
        if (!is_writable($filePath)) {
            throw new \Exception('Filepath ' . $filePath . ' should be writable');
        }
        $this->filePath = $filePath;
        $this->limit = $limit;
    }

    public function check(): bool
    {
        if (time() - filemtime($this->filePath) < $this->limit) {
            return false;
        }

        touch($this->filePath);
        return true;
    }
}
