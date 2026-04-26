<?php

if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

class Database {

    private $username;
    private $host;
    private $port;
    private $database;
    private $password;

    function __construct()
    {
        $this->username = $_ENV['DB_USER']     ?? getenv('DB_USER');
        $this->host     = $_ENV['DB_HOST']     ?? getenv('DB_HOST');
        $this->password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');
        $this->port     = (int) ($_ENV['DB_PORT'] ?? getenv('DB_PORT'));
        $this->database = $_ENV['DB_NAME']     ?? getenv('DB_NAME');
    }

    public function connection() {

        $connection = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);

        if ($connection->connect_error) {
            die("Connection error: " . $connection->connect_error);
        }

        return $connection;
    }

}