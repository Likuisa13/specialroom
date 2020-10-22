<?php

namespace Src\Controller;

use Src\Model\Room;
use HttpClient as GlobalHttpClient;

class RoomController
{
    private $db;

    private $http;
    private $roomModel;

    public function __construct($db)
    {
        $this->http         = new GlobalHttpClient();
        $this->roomModel    = new Room($db);

        $this->db               = $db;
    }

    public function getAllRoom()
    {
        $result     = $this->roomModel->findAll();
        $response   = $this->http->successResponse("Berhasil mendapatkan data ruangan", $result);
        return $response;
    }

    public function getRoom($id)
    {
        $result = $this->roomModel->find($id);
        if (!$result) {
            return $this->http->notFoundResponse();
        }
        $response   = $this->http->successResponse("Berhasil mendapatkan data ruangan", $result);
        return $response;
    }
}
