<?php
require_once 'webpush-auth.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

function sendNotification($subscriber, $notification) {
    $subscription = Subscription::create([
        "endpoint" => $subscriber['endpoint'],
        "authToken" => $subscriber['authToken'],
        "publicKey" => $subscriber['publicKey']
    ]);

    $payload = json_encode(array(
        "title" => $notification['title'], 
        "body" => $notification['body']
    ));

    $auth = array(
        'VAPID' => array(
            'subject' => 'https://github.com/Minishlink/web-push-php-example/',
            'publicKey' => WEBPUSH_KEYS['PUBLIC'],
            'privateKey' => WEBPUSH_KEYS['PRIVATE'], 
        ),
    );

    $webPush = new WebPush($auth);
    $res = $webPush->sendNotification($subscription, $payload);

    foreach ($webPush->flush() as $report) {
        $endpoint = $report->getRequest()->getUri()->__toString();

        if ($report->isSuccess()) {
            echo json_encode(array(
                "status" => "success",
                "message" => "Message sent successfully for subscription {$endpoint}."
            ));
        } else {
            echo json_encode(array(
                "status" => "failed",
                "message" => "Message failed to sent for subscription {$endpoint}: {$report->getReason()}"
            ));
        }
    }
}