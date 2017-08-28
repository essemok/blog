<h3>Оставить комментарий</h3>
<form class="c-add" method="post">
    Ваше имя:
    <?php if(isset($_SESSION['login'])): ?>
        <a name="name"><?=$name?></a><br><br>
    <?php else: ?>
        <input type="text" name="name" value="<?=$name?>">
    <?php endif; ?>
    Комментарий:
    <textarea name="c_text"><?=$c_text?></textarea>
    <input type="submit" value="Отправить">
</form>
