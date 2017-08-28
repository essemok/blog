<?php
$mUsers = M_Users::GetInstance();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title><?=$title?></title>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <link rel="stylesheet" type="text/css" media="screen" href="view/style.css">
</head>
<body>
<div class="page">
    <header>
        <div class="wrapper">
            <h1 id="sitename"><a href="index.php">Информатор 3000</a></h1>

            <div id="userblock">
                <?php if(isset($_SESSION['login'])): ?>
                    <?php
                    $uid = $mUsers->GetUid();
                    ?>

                    <a href="index.php?c=users&act=read&id=<?=$uid?>"><?=$_SESSION['login']?></a>
                    <a href='index.php?c=users&act=logout'>Выйти</a>
                <?php else: ?>
                    <a href='index.php?c=users&act=login'>Войти</a>
                    <a href="index.php?c=users&act=register">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <div class="wrapper">
            <?=$content?>
        </div>
    </main>

    <footer>
        <div class="wrapper">Спорыш С. &copy; <?=date("Y")?>
        </div>
    </footer>
</div>
</body>
</html>