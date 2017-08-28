<?php
$mUsers = M_Users::GetInstance();
// Может ли пользователь редактировать статью?
if (!$mUsers->Can('EDIT_ARTICLE'))
{
    //die('Отказано в доступе');
    header('location: view/error.php');
}
?>

<ul class="crumbs nolstyle llist">
    <li>
        <a href="index.php">Главная</a><span>&rarr;</span>
    </li>
    <li>
        Редактирование <i><?=$title?></i>
    </li>
</ul>

<?php if(isset($error)): ?>
    <p class="error"><?=$error?></p>
<?php endif; ?>
<form method="post">
    Название:
    <input type="text" name="title" value="<?=$title?>">
    Содержание:
    <textarea name="content"><?=$content?></textarea>
    <input type="submit" value="Сохранить">
    <a class="cancel" href="index.php?c=articles&act=read&id=<?=$_GET['id']?>">Отмена</a>
</form>
