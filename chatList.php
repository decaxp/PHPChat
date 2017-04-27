<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 26.04.2017
 * Time: 18:29
 */
include_once "mysql.php";

$sql="select count(*) as count from chat";
$sql2="select id,name from chat";

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

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!$result = $mysqli->query($sql)) {
   queryError($sql,$mysqli);
}

$chatCount=0;
$actor = $result->fetch_assoc();
if ($actor['count']!=0) {
    $chatCount = $actor['count'];

    if (!$result = $mysqli->query($sql2)) {
        queryError($sql, $mysqli);
    }

    $chatArr = array();
    while ($chat = $result->fetch_assoc()) {
        $chatArr[] = $chat;
    }
}