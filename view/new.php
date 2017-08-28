<ul class="crumbs nolstyle llist">
    <li>
        <a href="index.php">Главная</a><span>&rarr;</span>
    </li>
    <li>
        Добавить статью
    </li>
</ul>

<h2>Новая статья</h2>
<? if($error) :?>
    <b style="color: red;">Заполните все поля!</b>
<? endif ?>
<form method="post">
    Название:
    <input type="text" name="title" value="<?=$title?>">
    Содержание:
    <textarea name="content"><?=$content?></textarea>
    <input type="submit" value="Добавить">
    <a class="cancel" href="index.php">Отмена</a>
</form>
