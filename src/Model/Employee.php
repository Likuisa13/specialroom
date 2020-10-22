<?php

namespace src\Model;

class Employee
{
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
