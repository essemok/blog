<ul class="crumbs nolstyle llist">
    <li>
        <a href="index.php">Главная</a><span>&rarr;</span>
    </li>
    <li>
        Авторизация
    </li>
</ul>

<h2>Авторизация</h2>
<b style="color: red;"><?=$msg?></b>
<form class="user-action" method="post">
    Логин:
    <input type="text" name="login">
    Пароль:
    <input type="password" name="password">
    <input type="checkbox" name="remember" checked> Запомить меня
    <input type="submit" value="Войти">
</form>
