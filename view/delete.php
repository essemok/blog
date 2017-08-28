<?php
$mUsers = M_Users::GetInstance();
// Может ли пользователь удалять статью?
if (!$mUsers->Can('DELETE_ARTICLE'))
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
        Удаление <i><?=$title?></i>
    </li>
</ul>

<p>Вы точно хотите удалить статью "<?=$title?>"?</p>
<form method="post">
    <input type="submit" name="delete" value="Удалить">
    <input type="submit" name="cancel" value="Отмена">
</form>