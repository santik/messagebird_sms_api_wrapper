<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use MessageBird\Client;
use Santik\Sms\Application\FileBasedThroughputLimitChecker;
use Santik\Sms\Application\JsonRequestBasedMessagesCreator;
use Santik\Sms\Infrastructure\MessageBirdBasedSmsClient;
use Santik\Sms\Infrastructure\SmsSender;
use Symfony\Component\HttpFoundation\Request;

$mbKey = '*MB_KEY*';

$messageBird = new Client($mbKey);

//here we assuming that we have only 1 client
//if there will be multiple concurrent clients solution will not work
//in case of multiple clients queueing should be used
$filePath = dirname(__FILE__) . '/' . md5(FileBasedThroughputLimitChecker::class);
$limitChecker = new FileBasedThroughputLimitChecker($filePath, 1);

$senderClient = new MessageBirdBasedSmsClient($messageBird, $limitChecker);

$messageCreator = new JsonRequestBasedMessagesCreator();

$smsSender = new SmsSender($messageCreator, $senderClient);

try {
    $smsSender->send(Request::createFromGlobals());
    echo 'Message sent' . "\n";
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}
