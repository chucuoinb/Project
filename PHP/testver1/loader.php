<?php
/**
 * Created by PhpStorm.
 * User: Nam
 * Date: 2/18/2017
 * Time: 6:03 PM
 */
require_once("config.php");
class loader{
    private $_connection;
    private static $_instance; //The single instance
    public static function getInstance()
    {
        if (!self::$_instance)
            self::$_instance = new self();
        return self::$_instance;
    }
    private function __construct()
    {
        $this->_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        if (mysqli_connect_error()) {
            trigger_error("Failed to connection to MySQL: " . mysql_connect_error(), E_USER_ERROR);
        }
    }


    private function __clone()
    {
    }


    public function getConnection()
    {
        return $this->_connection;
    }
}
?>