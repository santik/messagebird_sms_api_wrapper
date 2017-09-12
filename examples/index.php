<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use MessageBird\Client;
use Santik\Sms\Application\JsonRequestBasedMessagesCreator;
use Santik\Sms\Application\SmsSender;
use Santik\Sms\Infrastructure\FileBasedThroughputLimitChecker;
use Santik\Sms\Infrastructure\MessageBirdBasedSmsClient;
use Santik\Sms\Infrastructure\UdhGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

//put your MessageBird key here
$mbKey = 'MB_KEY';

try {
    $messageBird = new Client($mbKey);

    //here we assuming that we have only 1 client
    //if there will be multiple concurrent clients solution will not work
    //in case of multiple clients queueing should be used
    $filePath = dirname(__FILE__) . '/' . md5(FileBasedThroughputLimitChecker::class);
    $limitChecker = new FileBasedThroughputLimitChecker($filePath, 1);

    $messageCreator = new JsonRequestBasedMessagesCreator();
    $senderClient = new MessageBirdBasedSmsClient($messageBird, $limitChecker, new UdhGenerator());

    $smsSender = new SmsSender($messageCreator, $senderClient);

    $smsSender->send(Request::createFromGlobals());
    $response = new JsonResponse(['message' => 'ok']);
} catch (\Exception $e) {
    $response = new JsonResponse(['message' => $e->getMessage()], 500);
}

$response->send();
