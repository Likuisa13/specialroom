<?php

use Src\Controller\RoomController;
use Src\System\DatabaseConnector;

require "../bootstrap.php";
require "../components/HttpClient.php";
require "index.php";

/* get class */
$dbConnection   = (new DatabaseConnector())->getConnection();
$http           = new HttpClient();

/* get request data */
$data   = file_get_contents('php://input');
$decode = json_decode($data);

$controller = new RoomController($dbConnection);

$response = $controller->getAllRoom();
if ($decode->room_code != "") {
    $response = $controller->getRoom($decode->room_code);
}

header($response['status_code_header']);
if ($response['body']) {
    echo $response['body'];
}
