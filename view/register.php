<ul class="crumbs nolstyle llist">
    <li>
        <a href="index.php">Главная</a><span>&rarr;</span>
    </li>
    <li>
        Регистрация
    </li>
</ul>

<h2>Регистрация</h2>
<b style="color: red;"><?=$msg?></b>
<form class="user-action" method="post">
    Логин:
    <input type="text" name="login" value="<?=$login?>">
    Пароль:
    <input type="password" name="password" value="<?=$password?>">
    Подтверждение пароля:
    <input type="password" name="password_confirm" value="<?=$password_confirm?>">
    E-mail:
    <input type="email" name="email" value="<?=$email?>">

    <input type="submit" value="Зарегистрироваться">
</form>
