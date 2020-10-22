<?php

namespace src\Model;

class Booking
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
                rm.room_name,
                em.`name`,
                br.booking_date,
                br.booking_time
            FROM
                booking_room br
            LEFT JOIN employee em ON br.employee_id = em.nik
            LEFT JOIN room_master rm ON br.room_code = rm.room_code
            WHERE
                rm.`status`=1;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findByRoom($id)
    {
        $statement = "
           SELECT
                rm.room_name,
                em.`name`,
                br.booking_date,
                br.booking_time,
                rm.`status`
            FROM
                booking_room br
            LEFT JOIN employee em ON br.employee_id = em.nik
            LEFT JOIN room_master rm ON br.room_code = rm.room_code
            WHERE
                rm.`room_code`= ?
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
