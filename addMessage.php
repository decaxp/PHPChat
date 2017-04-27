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

$message=$_POST['message'];
$message=test_input($message)??'';
$curID=$_POST['maxID'];


$id=test_input($_POST['id'])?$_POST['id']:-1;
$isAuth=false;
$userid=-1;
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

$sqlInsertMessage="insert into messages (chatid,userid,message) values(?,?,?)";
//добавляем пользователя
$stmt = $mysqli->prepare($sqlInsertMessage);
$stmt->bind_param('iis', $id,$userid,$message);
/* выполнение подготовленного запроса */
$stmt->execute();
//echo $id.'!!!!'.$curID;exit();
$sqlGetMessages="select users.name as username,messages.message as message,messages.id as id from users,messages where users.id=messages.userid and messages.chatid=? and messages.id>?";
$stmt = $mysqli->prepare($sqlGetMessages);
$stmt->bind_param('ii', $id,$curID);
/* выполнение подготовленного запроса */
$stmt->execute();

$res = $stmt->get_result();

/* Выбрать значения */
$messArr=array();
$maxID=-1;
while ($row = $res->fetch_assoc()) {
    if (!is_null($row['username']) and !is_null($row['message'])) {
        $messArr[] = [$row['username'], $row['message'],$row['id']];
    }
}

$stmt->close();

echo json_encode($messArr,JSON_FORCE_OBJECT);