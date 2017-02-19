<?php
/**
 * Created by PhpStorm.
 * User: Nam
 * Date: 2/18/2017
 * Time: 11:12 PM
 */
require_once("const.php");
require_once("config.php");
require_once("loader.php");
require_once("function.php");
$DB = new Funtion();
//$response = array();
if(isset($_POST[USERNAME]) && isset($_POST[PASSWORD]) && isset($_POST[EMAIL]))
{
    $username = $_POST[USERNAME];
    $password = $_POST[PASSWORD];
    $email = $_POST[EMAIL];
    $display_name = isset($_POST[DISPLAYNAME]) ? $_POST[DISPLAYNAME] : null;
    $birthday = isset($_POST[BIRTHDAY]) ? $_POST[BIRTHDAY] : null;
    $gender = isset($_POST[GENDER]) ? $_POST[GENDER] : null;
//    $gcm_id = isset($_POST[GCM]) ? $_POST[GCM] : null;
    if($DB->IsUserExisted($username))
    {
        $DB->ResponseMessage(CODE_USER_EXIST,"user exist",null);
    }else
    {
        if($DB->IsEmailExisted($email))
        {
            $DB->ResponseMessage(CODE_EMAIL_EXIST,"email exist", null);
        }else{
            if($DB->StoreUser($username, $password, $email, $display_name, $birthday, $gender))
            {
                $DB->ResponseMessage(CODE_OK,"",null);
            }else
                $DB->ResponseMessage(CODE_FAIL,"register failed",null);

        }
    }
}else
{
    $DB->ResponseMessage(CODE_INVALID,"invalid",null);
}
?>