<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use MessageBird\Client;
use Santik\Sms\Application\JsonRequestBasedMessagesCreator;
use Santik\Sms\Infrastructure\MessageBirdBasedSmsClient;
use Santik\Sms\Infrastructure\SmsSender;
use Symfony\Component\HttpFoundation\Request;

$mbKey = 'FaLrUQ5nBINSB1lgrQ7s7aztt';

$messageBird = new Client($mbKey);
$senderClient = new MessageBirdBasedSmsClient($messageBird);
$messageCreator = new JsonRequestBasedMessagesCreator();

$smsSender = new SmsSender($messageCreator, $senderClient);

try {
    $smsSender->send(Request::createFromGlobals());
    echo 'Message sent' . "\n";
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}
