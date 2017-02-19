<?php
/**
 * Created by PhpStorm.
 * User: Nam
 * Date: 2/18/2017
 * Time: 6:09 PM
 */
require_once("config.php");
require_once("const.php");
require_once("loader.php");

class Funtion
{
    private $conn;

    function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $this->conn->query("SET NAMES utf8");
        $this->conn->query("SET CHARACTER SET utf8");
        $this->conn->set_charset('utf8');
        $this->conn->set_charset('utf-8');
    }

    function getConn()
    {
        return $this->conn;
    }

    /**
     * Store new user to database
     * @param type $password :password
     * @param type $username :username
     */
    function StoreUser($username, $password, $email, $displayname, $birthday, $gender)
    {
        $db = loader::getInstance();
        $mysqli = $db->getConnection();
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "INSERT INTO account (username,password,email,displayname,birthday,gender)
                VALUES (?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssi", $username,$password,$email,$displayname,$birthday,$gender);

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $sql = "SELECT * FROM account WHERE username = '" . $username . "'";
            $result = $mysqli->query($sql);
            if ($result) {
                $user = mysqli_fetch_assoc($result);
                if ($user) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**check username exist
     * @param $username
     * @return bool
     */
    function IsUserExisted($username)
    {
        $sql = "SELECT * FROM account
                WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    /**check email exist
     * @param $email
     * @return bool
     */
    function IsEmailExisted($email)
    {
        $sql = "SELECT * FROM account
                WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    function Login($username, $password)
    {
        $db = loader::getInstance();
        $mysqli = $db->getConnection();
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT * FROM account
                where username = '" . $username . "'
                AND password = '" . $password . "'
                LIMIT 1";
        $result = $mysqli->query($sql);
        if (mysqli_num_rows($result) > 0) {
            do
            {
            $token = $this->CreateToken(50);
            }while($this->IsTokenEsixt($token));
            $data = array();
            $data[TOKEN]= $token;
            $this->ResponseMessage("11","aa",$data);
            $sql = "UPDATE account
                    SET token = '" . $token . "'
                    WHERE username = '" . $username . "'";
            $result = $mysqli->query($sql);
            if ($result)
                return $token;
            else
                return false;
        } else
            return false;
    }


    /** check token exist
     * @param $token
     * @return bool
     */
    function IsTokenEsixt($token)
    {
        $sql = "SELECT * FROM account
                WHERE token = '" . $token . "'
                LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if (mysqli_num_rows($result) > 0)
            return true;
        return false;
    }

    /**
     * @param $code : success or fail
     * @param $message
     * @param $data
     */
    function ResponseMessage($code, $message, $data)
    {
        $response = array();
        $response[CODE] = $code;
        $response[MESSAGE] = $message;
        $response[DATA] = $data;
        echo json_encode($response);
    }

    //create token
    function CreateToken($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }


}

?>