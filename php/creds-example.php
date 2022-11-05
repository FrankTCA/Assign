<?php
class Creds {
    /*
     * Put your credentials here!
     * First four are for the database
     * Please place a random password in $aes_password
     * Put any number in $hash_cost, higher numbers will take more computing power
     * The $development variable will allow some printing of data to the client machine, TURN THIS OFF BEFORE USING IN PRODUCTION!!!
     */

    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "password";
    private $database = "assign";

    private $aes_password = "anythinggoesherejustmakeitrandom";
    private $hash_cost = 15;
    private $development = false;

    function get_host() {
        return $this->host;
    }

    function get_username() {
        return $this->username;
    }

    function get_password() {
        return $this->password;
    }

    function get_database() {
        return $this->database;
    }

    function get_aes_password() {
        return $this->aes_password;
    }

    function get_hash_cost() {
        return $this->hash_cost;
    }

    function is_development() {
        return $this->development;
    }
}
