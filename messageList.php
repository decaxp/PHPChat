<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 27.04.2017
 * Time: 15:47
 */
session_start();
include_once "security.php";
include_once "mysql.php";

$id=test_input($_GET['id'])??-1;

$sqlCheckChat="select count(*) as count from chat where id=?";
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

//проверяем авторизован ли пользователь в этом чате, иначе его перекидываем на стр авториз-и
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
if (!$isAuth){
    header('Location: addtochat.php?id='.$id);
    exit();
}

$sqlGetMessages="select users.name as username,messages.message as message,messages.id as id from users,messages where users.id=messages.userid and messages.chatid=?";
$stmt = $mysqli->prepare($sqlGetMessages);
$stmt->bind_param('i', $id);
/* выполнение подготовленного запроса */
$stmt->execute();

$res = $stmt->get_result();

/* Выбрать значения */
$messArr=array();
$maxID=-1;
while ($row = $res->fetch_assoc()) {
    if (!is_null($row['username']) and !is_null($row['message'])) {
        $messArr[] = [$row['username'], $row['message']];
        $maxID=$row['id'];
    }
}

$stmt->close();