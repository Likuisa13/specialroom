<?php

namespace Src\System;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;

require "../vendor/autoload.php";

class TelegramConnector
{
    private $token;
    private $https;

    public function __construct()
    {
        $this->token    = getenv('TOKEN_TELEGRAM');
        $this->https    = getenv('HTTPS_LOC'); //change to HTTPS_DEV if running on dev
    }

    public function runAutoReply()
    {
        try {
            $bot = new Client($this->token);

            $bot->command('ping', function ($message) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), 'pong!');
            });

            $bot->run();
        } catch (\TelegramBot\Api\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getUpdates()
    {
        $get        = new BotApi($this->token);
        $response   = $get->getUpdates();

        /* read message */
        $lastMessage = count($response) - 1;
        $lastMessage = $response[$lastMessage];
        $this->readMessage($lastMessage->getupdateId());

        return $response;
    }

    public function readMessage($update_id)
    {
        $lastUpdateId   = $update_id + 1;
        $get            = new BotApi($this->token);
        $response       = $get->getUpdates($lastUpdateId);

        return $response;
    }

    public function sendMessage($chatid, $message)
    {
        $send = new BotApi($this->token);
        $send->sendMessage($chatid, $message);
    }

    public function removeWebhook()
    {
        $remove = new BotApi($this->token);
        $response = $remove->deleteWebhook();

        return $response;
    }

    public function setWebhook()
    {
        $set = new BotApi($this->token);
        $response = $set->setWebhook($this->https);

        return $response;
    }
}
