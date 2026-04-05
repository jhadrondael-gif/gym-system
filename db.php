<?php

class Database {

    private $username;
    private $host;
    private $port;
    private $database;
    private $password;

    function __construct()
    {
        $this->username = "root";
        $this->host = "localhost";
        $this->password = "";
        $this->port = 3306;
        $this->database = "gym_system_db";
    }

    public function connection() {

        $connection = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);

        if ($connection->connect_error) {
            die("Connection error" . $connection->connect_error);
        } else {
            return $connection;
        }

    }

}