<?php

require_once "config.inc.php";

class Database
{
    var $database;

    public function __construct()
    {
        $this->database = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME) or die("Keine Verbindung zum SQL-Server!");
    }

    function __deconstruct()
    {
        if ($this->isConnected()) {
            $this->database->close();
        }
    }

    public function isConnected()
    {
        return $this->database != null;
    }

    public function query($sql)
    {
        if ($this->isConnected())
            return $this->database->query($sql);
    }

    public function insert($sql)
    {
        if ($this->query($sql)) {
            return $this->database->insert_id;
        }
    }
}

?>