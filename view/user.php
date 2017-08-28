<ul class="crumbs nolstyle llist">
    <li>
        <a href="index.php">Главная</a><span>&rarr;</span>
    </li>
    <li>
        <a href="index.php?c=users">Пользователи</a><span>&rarr;</span>
    </li>
    <li>
        Профиль пользователя <?=$user['login']?>
    </li>
</ul>

<h2>Профиль <?=$user['login']?></h2>
<?php if(isset($_SESSION['login'])): ?>
    <p>E-mail: <?=$user['email']?></p>
<?php endif; ?>
<p class="info">Сейчас на сайте: <i><?=$info?></i></p>