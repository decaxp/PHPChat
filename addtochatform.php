<?php
$chatError=isset($_GET['chatError'])?(int)$_GET['chatError']:0;
$username=isset($_GET['username'])?$_GET['username']:'';
$id=isset($_GET['id'])?$_GET['id']:'-1';
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
    <link href="css/jquery-ui.min.css" rel="stylesheet">


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
        <h3 class="text-muted">CHAT Dolgov</h3>
    </div>

    

    <div class="jumbotron">
        <p class="lead">Инициализация чата</p>
        <form id="addChat" name="addChat" action="addtochat.php" method="GET"">

        <label for="username">Имя пользователя</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Имя пользователя" value="<?= isset($username)?$username:''; ?>">
        <input type="hidden" name="id" value="<?= $id; ?>">
        <?php if ($chatError==1){ ?>

            <div class="marginTop10 alert alert-danger ">
                <strong>Ошибка!</strong> Пользователь с таким именем уже существует. Придумайте новое имя!
            </div>

        <?php } ?>
        <input type="submit" name="submit"  class="btn btn-primary fright" value="Присоединиться">
        <a href="/" class="btn btn-default">Вернуться на главную</a>
        </form>
    </div>
</div>
</body>
</html>