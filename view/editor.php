<?php
$mUsers = M_Users::GetInstance();
// Может ли пользователь редактировать статьи?
if (!$mUsers->Can('EDIT_ARTICLE'))
{
    //die('Отказано в доступе');
    header("location: view/error.php");
}
?>

<ul class="crumbs nolstyle llist">
    <li>
        <a href="index.php">Главная</a><span>&rarr;</span>
    </li>
    <li>
        Редактор
    </li>
</ul>

<ul class="nolstyle editor">
    <li class="new">
        <b><a href="index.php?c=articles&act=add">Новая статья</a></b>
    </li>

    <?php foreach ($articles as $article): ?>
        <li>
            <a href="index.php?c=articles&act=edit&id=<?=$article['id_article']?>"><?=$article['title']?></a>
            <hr/>
        </li>
    <?php endforeach; ?>
</ul>
