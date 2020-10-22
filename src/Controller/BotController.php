<?php

namespace Src\Controller;

use Src\Model\Room;
use HttpClient as GlobalHttpClient;
use Src\System\TelegramConnector;

class BotController
{
    private $db;
    private $requestMethod;
    private $id;

    private $http;
    private $roomModel;
    private $telegram;

    public function __construct($db, $requestMethod, $id)
    {
        $this->http         = new GlobalHttpClient($requestMethod);
        $this->roomModel    = new Room($db);
        $this->telegram     = new TelegramConnector();

        $this->db               = $db;
        $this->requestMethod    = $requestMethod;
        $this->id               = $id;
    }

    public function processRequest()
    {
        $response = $this->telegram->setWebhook();

        var_dump($response);
    }

    public function setWebhookRequest()
    {
        $response = $this->telegram->setWebhook();

        var_dump($response);
    }

    public function removeWebhookRequest()
    {
        $response = $this->telegram->removeWebhook();

        var_dump($response);
    }
}
