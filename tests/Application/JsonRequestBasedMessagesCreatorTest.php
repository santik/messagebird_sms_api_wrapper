<?php

declare(strict_types=1);

namespace Santik\Sms\Application;

use PHPUnit\Framework\TestCase;
use Santik\Sms\Domain\Message;
use Symfony\Component\HttpFoundation\Request;

class JsonRequestBasedMessagesCreatorTest extends TestCase
{
    public function testCreate_withCorrectParameterForSingleMessage_ShouldReturnArrayWith1Message()
    {
        $data = [
            'recipient' => 'recipient',
            'originator' => 'originator',
            'message' => 'message',
        ];
        $request = new Request(['data' => json_encode($data)]);

        $creator = new JsonRequestBasedMessagesCreator();
        $messages = $creator->create($request);

        $this->assertInternalType('array', $messages);
        $this->assertEquals(1, count($messages));
        $this->assertInstanceOf(Message::class, $messages[0]);
    }

    public function testCreate_withCorrectParameterForMultipleMessages_ShouldReturnArrayWithMultipleMessages()
    {
        $data = [
            'recipient' => 'recipient',
            'originator' => 'originator',
            'message' => 'message message message message message message message message message message message message message message message message message message message message message message message message message message ',
        ];
        $request = new Request(['data' => json_encode($data)]);

        $creator = new JsonRequestBasedMessagesCreator();
        $messages = $creator->create($request);

        $this->assertInternalType('array', $messages);
        $this->assertEquals(2, count($messages));
        $this->assertInstanceOf(Message::class, $messages[0]);
    }

    public function testCreate_withInCorrectRequestParameterKey_ShouldThrowException()
    {
        $data = [
            'recipient' => 'recipient',
            'originator' => 'originator',
            'message' => 'message',
        ];
        $request = new Request(['INCORRECT' => json_encode($data)]);

        $creator = new JsonRequestBasedMessagesCreator();

        $this->expectException(\InvalidArgumentException::class);

        $creator->create($request);
    }

    /**
     * @dataProvider wrongParameters
     */
    public function testCreate_withIncorrectRequestParameters_ShouldThrowException($data, $expected)
    {
        $request = new Request(['data' => json_encode($data)]);

        $creator = new JsonRequestBasedMessagesCreator();

        $this->expectException($expected);

        $creator->create($request);
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
