<?php

namespace src\Model;

class Room
{
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT
                room_code, room_name, room_capacity, location, flor, status
            FROM
                room_master;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT
                room_code, room_name, room_capacity, location, flor, status
            FROM
                room_master
            WHERE room_code = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function checkAvailable($date = null, $room = null)
    {
        $date = date("Y-m-d");
        $sqlRoomBydate = "
            SELECT
                br.employee_id,
                br.booking_date,
                br.booking_time,
                br.description_id,
                br.duration,
                br.room_code,
                rm.location
            FROM
                booking_room br
            LEFT JOIN room_master rm ON br.room_code = rm.room_code
            WHERE
                br.booking_date = ?
            AND rm.location = ?
            AND br.booking_time BETWEEN '09:00:00'
            AND '18:00:00'
            ORDER BY
                br.booking_time, br.booking_date ASC;
        ";

        $statement = $this->db->prepare($sqlRoomBydate);
        $statement->execute(array($date, $room));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $lastArray          = count($result) - 1;
        $previousValue      = null;
        $return  = [];
        foreach ($result as $key => $result) {

            $duration   = $result['duration'];
            $start      = strtotime($result['booking_time']);
            $finish     = date("H:i:s", strtotime('+' . $duration . ' minutes', $start));

            $timeNow = '09:00:00';

            if ($timeNow < $result['booking_time']) {
                if ($previousValue) {
                    $freeSpace = $previousValue . " s/d " . $result['booking_time'];
                }

                if ($key == 0) {
                    $startFree = $timeNow;
                    $freeSpace = $startFree . " s/d " . $result['booking_time'];
                }

                if ($key == $lastArray) {
                    $freeSpace = $finish . " s/d " . "18:00:00";
                }

                $dataRoom = $this->find($result['room_code']);

                print_r($dataRoom);
                // echo $dataRoom;
                echo "\n";
                echo "\n";

                // $return[] = [
                //     "room" => $result['room_code'],
                //     "free" => $freeSpace,
                // ];
                $return[] = $freeSpace;
                // $return[] = $result['room_code'];
                $previousValue = $finish;
            }
        }
        return $return;
    }
}
