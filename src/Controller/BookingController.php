<?php

namespace Src\Controller;

use Src\Model\Booking;
use src\Model\Room;
use Src\System\DatabaseConnector;

class BookingController
{
    private $db;
    private $roomModel;

    public function __construct($db)
    {
        $this->roomModel = new Room($db);
    }

    public function getRoomAvailable()
    {
        $result     = $this->roomModel->checkAvailable();
        return $result;
    }
}
