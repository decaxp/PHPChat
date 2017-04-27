<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 26.04.2017
 * Time: 18:42
 */
session_start();

include_once "mysql.php";
include_once "security.php";

function queryError($sql,$mysqli){
    // О нет! запрос не удался.
    echo "Извините, возникла проблема в работе сайта.";
    // И снова: не делайте этого на реальном сайте, но в этом примере мы покажем,
    // как получить информацию об ошибке:
    echo "Ошибка: Наш запрос не удался и вот почему: \n";
    echo "Запрос: " . $sql . "\n";
    echo "Номер_ошибки: " . $mysqli->errno . "\n";
    echo "Ошибка: " . $mysqli->error . "\n";
    exit;
}

$sqlInsertChat="insert into chat (name) values (?)";
$sqlGetChatId="select id from chat where chat.name=?";
$sqlInsertUser="insert into users (chatid,name) values (?,?)";
$sqlGetUserId="select id from users where users.chatid=?";
$host=$_SERVER['HTTP_HOST'];

$username=test_input($_POST['username']);
$chatname=test_input($_POST['chatname']);


if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
//добавляем чат
$stmt = $mysqli->prepare($sqlInsertChat);
$stmt->bind_param('s', $chatname);
/* выполнение подготовленного запроса */
$stmt->execute();
if (isset($stmt->error) and !empty($stmt->error)){
    header('Location:'.$host.'/index.php?chatError=1&username='.$username.'&chatname='.$chatname);
}
$stmt->close();

//находим id
$stmt = $mysqli->prepare($sqlGetChatId);
$stmt->bind_param('s', $chatname);
/* выполнение подготовленного запроса */
$stmt->execute();

$stmt->bind_result($chatid);
$stmt->fetch();
$stmt->close();

//добавляем пользователя
$stmt = $mysqli->prepare($sqlInsertUser);
$stmt->bind_param('is', $chatid,$username);
/* выполнение подготовленного запроса */
$stmt->execute();

//находим id пользователя
$stmt = $mysqli->prepare($sqlGetUserId);
$stmt->bind_param('i', $chatid);
/* выполнение подготовленного запроса */
$stmt->execute();

$stmt->bind_result($userid);
$stmt->fetch();
$stmt->close();

//поместить в сессию id своего чата, свое имя для этого чата
if(!isset($_SESSION['chatArray'])){
    $_SESSION['chatArray']=array();
}
$_SESSION['chatArray'][]=array($chatid,$username,$userid);

header('Location: http://'.$host.'/index.php');

