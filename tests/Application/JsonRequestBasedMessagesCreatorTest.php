<?php

declare(strict_types=1);

namespace Santik\Sms\Application;

use PHPUnit\Framework\TestCase;
use Santik\Sms\Domain\Message;
use Symfony\Component\HttpFoundation\Request;

final class JsonRequestBasedMessagesCreatorTest extends TestCase
{
    public function testCreate_withCorrectParameterForSingleMessage_ShouldReturnArrayWith1Message()
    {
        $data = [
            'recipient' => 'recipient',
            'originator' => 'originator',
            'message' => 'message',
        ];
        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode($data));

        $creator = new JsonRequestBasedMessagesCreator();
        $message = $creator->create($request->reveal());

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals(1, count($message->message()));
    }

    /**
     * @dataProvider wrongParameters
     */
    public function testCreate_withIncorrectRequestParameters_ShouldThrowException($data, $expected)
    {
        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode($data));

        $creator = new JsonRequestBasedMessagesCreator();

        $this->expectException($expected);

        $creator->create($request->reveal());
    }

    public function wrongParameters()
    {
        return [
            [
                [
                    'originator' => 'originator',
                    'message' => 'message',
                ],
                \InvalidArgumentException::class
            ],
            [
                [
                    'recipient' => 'recipient',
                    'message' => 'message',
                ],
                \InvalidArgumentException::class
            ],
            [
                [
                    'recipient' => 'recipient',
                    'originator' => 'originator',
                ],
                \InvalidArgumentException::class
            ],
        ];
    }
}
