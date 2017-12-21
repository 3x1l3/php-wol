<?php

class Database extends mysqli
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "j";
    private $database = "php-wol";
    private $mysqli;

    function __construct()
    {
        // Create connection
        parent::__construct($this->servername, $this->username, $this->password);

        // Check connection
        if ($this->connect_error) {
            die("Connection failed: " . $this->connect_error);
        }

        //check if database exists
        $result = $this->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$this->database}'");
        if (mysqli_num_rows($result) <= 0) $this->create();
        else $this->query("USE `{$this->database}`");
    }

    public function loadConfig($filePath)
    {

        $configs = include($filePath);
        foreach ($configs as $key => $val) {
            $this->$key = $val;
        }

    }

    /**
     * creates a new database
     */
    function create()
    {
        $query = "
CREATE DATABASE IF NOT EXISTS `{$this->database}`;
USE `{$this->database}`;
CREATE TABLE IF NOT EXISTS server (
  id varchar(45) NOT NULL PRIMARY KEY,
  name varchar(30) NOT NULL,
  ip varchar(16) NOT NULL,
  mac varchar(20) UNIQUE,
  broadcast varchar(16)
);

CREATE TABLE IF NOT EXISTS user (
  id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  username varchar(30) NOT NULL UNIQUE,
  password varchar(128) NOT NULL,
  level int NOT NULL
);

INSERT INTO user VALUES (1, 'admin', 'f21c36829f29ba240b1de7d9487b5aa9bacf4e7309f8a61c84591e4f0642cf16fa804d66613778e315b64f377ceae352eb3c6be44073c15e7775983c69763a3a', 3);
		";
        $this->multi_query($query);
    }
}
