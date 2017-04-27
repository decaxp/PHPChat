<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 26.04.2017
 * Time: 21:27
 */
include_once "security.php";
include_once "mysql.php";

session_start();
$id=test_input($_GET['id'])??-1;
$isAuth=false;
$host=$_SERVER['HTTP_HOST'];
if (isset($_SESSION['chatArray']) and !empty($_SESSION['chatArray'])) {
    foreach ($_SESSION['chatArray'] as $chatItem) {
        if ($chatItem[0] == $id) {
            $isAuth = true;
            break;
        }
    }
}


$sqlCheckChat="select count(*) as count from chat where id=?";
$sqlChechUser="select count(*) as count from users where chatid=? and name=?";
$sqlNewUser="insert into users (name,chatid) values(?,?)";
$sqlGetUserId="select id from users where users.chatid=? and users.name=?";

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$stmt = $mysqli->prepare($sqlCheckChat);
$stmt->bind_param('i', $id);
/* выполнение подготовленного запроса */
$stmt->execute();

$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
//echo $count;exit;
if ($count==0){
    header('Location: index.php');
    exit();
}

if (!$isAuth){
    if (empty($_GET['username'])){
        header('Location: http://'.$host.'/addtochatform.php?id='.$id);
    }else{
        //проверяем есть ли в базе
        $username=test_input($_GET['username']);
//        echo $username,' ',$id;exit();
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        $stmt = $mysqli->prepare($sqlChechUser);
        $stmt->bind_param('is', $id,$username);
        /* выполнение подготовленного запроса */
        $stmt->execute();

        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
//        echo $count;exit();
        if ($count==0){
            //добавляем и переходим на страницу чата
            if ($mysqli->connect_errno) {
                echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }

            $stmt = $mysqli->prepare($sqlNewUser);
            $stmt->bind_param('si', $username,$id);
            /* выполнение подготовленного запроса */
            $stmt->execute();


            //находим id пользователя
            $stmt = $mysqli->prepare($sqlGetUserId);
            $stmt->bind_param('is', $id,$username);
            /* выполнение подготовленного запроса */
            $stmt->execute();

            $stmt->bind_result($userid);
            $stmt->fetch();
            $stmt->close();

            //+добавить в сессию
            if(!isset($_SESSION['chatArray'])){
                $_SESSION['chatArray']=array();
            }
            $_SESSION['chatArray'][]=array($id,$username,$userid);
//            var_dump($_SESSION);exit();
            header('Location: chat.php?id='.$id);
        }else{
            header("Location: addtochatform.php?chatError=1&id=".$id.'&username='.$username);
        }
    }

    // добавляемся
}else{
    //переходим на страницу чата
    header('Location: chat.php?id='.$id);
}