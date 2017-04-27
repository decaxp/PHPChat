<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 27.04.2017
 * Time: 15:45
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

$id=test_input($_GET['id'])?$_GET['id']:-1;
$userid=-1;
$isAuth=false;
$host=$_SERVER['HTTP_HOST'];
if (isset($_SESSION['chatArray']) and !empty($_SESSION['chatArray'])) {
    foreach ($_SESSION['chatArray'] as $chatItem) {
        if ($chatItem[0] == $id) {
            $isAuth = true;
            $userid=$chatItem[2];
            break;
        }
    }
}


if (!$isAuth){
    echo -1;//не авторизован
    exit();
}


$sqlDelActivate="delete from activate where userid=? and chatid=?";
$stmt = $mysqli->prepare($sqlDelActivate);
$stmt->bind_param('ii', $userid,$id);
/* выполнение подготовленного запроса */
$stmt->execute();
$stmt->close();
