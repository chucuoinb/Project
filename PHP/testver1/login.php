<?php
/**
 * Created by PhpStorm.
 * User: Nam
 * Date: 2/19/2017
 * Time: 12:09 AM
 */
require_once("const.php");
require_once("config.php");
require_once("loader.php");
require_once("function.php");
$DB = new Funtion();
$data = array();
if(isset($_POST[USERNAME]) && isset($_POST[PASSWORD])){
    $username = $_POST[USERNAME];
    $password = $_POST[PASSWORD];
    if($DB->IsUserExisted($username))
    {
        $token = $DB->Login($username,$password);
        if($token)
        {
            $data[USERNAME] = $username;
            $data[TOKEN] = $token;
            $DB->ResponseMessage(CODE_OK,"",$data);
        }else
            $DB->ResponseMessage(CODE_FAIL,"not match",null);
    }else
        $DB->ResponseMessage(CODE_USER_NOT_EXIST,"not exist",null);
}
?>