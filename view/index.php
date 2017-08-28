<?php $mUsers = M_Users::GetInstance(); ?>
<div class="menu">
    <?php if ($mUsers->Can('EDIT_ARTICLE')): ?>
        <b><a class="edlink" href="index.php?c=articles&act=editor">К редактору</a></b>
    <?php endif; ?>

    <a class="users" href="index.php?c=users">Пользователи</a>
</div>

<ul class="nolstyle">
    <?php foreach ($articles as $article): ?>
        <li>
            <h3>
                <a href="index.php?c=articles&act=read&id=<?=$article['id_article']?>"><?=$article['title']?></a>
            </h3>
            <p><?=M_Articles::articles_intro($article['content'], 200)?></p>
        </li>
        <hr>
    <?php endforeach; ?>

    <?php if(isset($_SESSION['login'])): ?>
    <ul class="nolstyle editor">
        <li class="new">
                <b><a href="index.php?c=articles&act=add">Новая статья</a></b>
        </li>
    </ul>
    <?php endif; ?>
</ul>
