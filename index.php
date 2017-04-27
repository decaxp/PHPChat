<?php
    session_start();
    include_once "chatList.php";
    $chatError=$_GET['chatError']??0;
    $username=$_GET['username']??'';
    $chatname=$_GET['chatname']??'';
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


    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="header">
        <span class="h3class text-muted">CHAT Dolgov</span>
        <a href="/" class="btn ">Вернуться на главную</a>
    </div>

    <div class="jumbotron">
        <p class="lead">Инициализация чата</p>


        <form id="initChat" name="initChat" action="chatInit.php" method="POST"">

                    <label for="username">Имя пользователя</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Имя пользователя" value="<?=$chatError==1?$username:''; ?>">


                    <label for="chatname">Название чата</label>
                    <input type="text" class="form-control" id="chatname" name="chatname" placeholder="Название чата" value="<?= $chatError==1?$chatname:''; ?>">
            <?php if ($chatError==1){ ?>

                    <div class="marginTop10 alert alert-danger ">
                        <strong>Ошибка!</strong> Чат с таким именем уже существует. Придумайте новое имя!
                    </div>

            <?php } ?>
                    <input type="submit" name="submit"  class="btn btn-primary fright" value="Начать чат">
        </form>
        <br>
        <a href="delses.php" class="btn btn-danger">Удалить сессию</a>
    </div>


    <?php if($chatCount!=0){ ?>
        
        <hr>

        <h4>Название чата</h4>

        <?php foreach ($chatArr as $chat){?>
            <div class="row">
                <div class="col-md-4"><?= $chat['name']; ?></div>
                <div class="col-md-8"><a href="/addtochat.php?id=<?= $chat['id']; ?>">Присоединиться</a></div>
            </div>
        <?php } ?>
    <?php } ?>

</div>
<br><br>
</body>
</html>