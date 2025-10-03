<?php
require_once __DIR__ . '/../renew.php';
class RenewRepository
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
}
