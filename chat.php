<?php
session_start();
include_once "security.php";
include_once "messageList.php";
$id=$_GET['id'];
$id=test_input($id)?$id:-1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-control" content="NO-CACHE">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CHAT Dolgov</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://yastatic.net/jquery/3.1.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/activate.js"></script>


    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="header">
        <span class="h3class text-muted">CHAT Dolgov</span>
        <a href="/" class="btn ">Вернуться на главную</a>
    </div>

    <div class="row">
        <div id="sidebar">
            <center>Юзеры онлайн</center>
            <div id="usersOnline">

            </div>
        </div>
        <div id="chatWindow">
            <input type="hidden" id="maxID" name="maxID" value="<?= $maxID; ?>">
            <div id="messages">
                <?php foreach ($messArr as $row){ ?>
                    <div class="item marginBottom10">
                        <span><i><?= $row[0]; ?></i> написал (-а):</span>
                        <div class="text-message"><?= $row[1]; ?></div>
                    </div>
                <?php } ?>
            </div>
            <div id="newmessage">
                <form id="addMessage" name="addMessage" action="addMessage.php" method="POST"">
                <input type="hidden" id="chatid" name="chatid" value="<?= $id; ?>">
                <textarea class="form-control" id="newMessage" name="newMessage" placeholder="Новое сообщение"></textarea>
                <button type="button" onclick="ajaxSend()" class="marginTop10 btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>

</div>
<br><br>
<script>
    function ajaxSend() {
        var message=$('#newMessage').val();
        var id=$('#chatid').val();
        var maxID=$('#maxID').val();
        var data={'message':message,'id':id,'maxID':maxID};

        $.ajax({
            type: "POST",
            url: "addMessage.php",
            data: data,
//            contentType: "application/json; charset=utf-8",
//            dataType: "json",
            success: function(data){
                $('#newMessage').val('');
                var obj=JSON.parse(data);

                var str="";
                var maxID=1;

                for(var key in obj){
                    str+='<div class="item marginBottom10"><span><i>';
                    str+=obj[key][0];
                    str+='</i> написал (-а):</span><div class="text-message">';
                    str+=obj[key][1];
                    str+="</div></div>";
                    maxID=obj[key][2];
                }

                $('#messages').append(str);
                $('#maxID').val(maxID);
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }


    function ajaxReceive() {
        var id=$('#chatid').val();
        var maxID=$('#maxID').val();

        var data={'id':id,'maxID':maxID};

        $.ajax({
            type: "POST",
            url: "getMessages.php",
            data: data,
//            contentType: "application/json; charset=utf-8",
//            dataType: "json",
            success: function(data) {
                console.log(data);
                if (data != -1) {
                    $('#newMessage').val('');
                    var obj = JSON.parse(data);

                    var str = "";
                    var maxID = 1;

                    for (var key in obj) {
                        str += '<div class="item marginBottom10"><span><i>';
                        str += obj[key][0];
                        str += '</i> написал (-а):</span><div class="text-message">';
                        str += obj[key][1];
                        str += "</div></div>";
                        maxID = obj[key][2];
                    }

                    $('#messages').append(str);
                    $('#maxID').val(maxID);
                }
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }

    function ajaxUsersOnline() {
        var id=$('#chatid').val();
        var data={'id':id};
        $.ajax({
            type: "POST",
            url: "getUsers.php",
            data: data,
            beforeSend:function () {
                $('#usersOnline').children().remove();
            },
            success: function(data) {
                var obj=JSON.parse(data);
//                console.log(data);
                var str="";
                for (var key in obj){
                    str+="<div><center>"+obj[key]+"</center></div>";
                }
                $('#usersOnline').append(str);
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }

    setInterval(ajaxReceive, 4000);
    setInterval(ajaxUsersOnline, 20000);
</script>
</body>
</html>