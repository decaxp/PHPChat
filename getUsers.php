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

$id=test_input($_POST['id'])?$_POST['id']:-1;
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

$sqlCheckActivate="select users.name as username from activate,users where activate.userid=users.id and activate.chatid=?";
$stmt = $mysqli->prepare($sqlCheckActivate);
$stmt->bind_param('i', $id);
/* выполнение подготовленного запроса */
$stmt->execute();

$res=$stmt->get_result();
$users=array();
while($row=$res->fetch_assoc()){
    if (!is_null($row['username'])){
        $users[]=$row['username'];
    }
}

$stmt->close();

echo json_encode($users,JSON_FORCE_OBJECT);
