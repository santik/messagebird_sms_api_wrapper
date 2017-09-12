<?php

declare(strict_types=1);

namespace Santik\Sms\Infrastructure;

use PHPUnit\Framework\TestCase;

class UdhGeneratorTest extends TestCase
{
    public function testGenerate_willReturnUdh()
    {
        $expectedUdh = '0500036f0502';

        $generator = new UdhGenerator();
        $udh = $generator->generate(5, 2, 111);

        $this->assertEquals($udh, $expectedUdh);
    }
}
