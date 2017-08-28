<?php $mUsers = M_Users::GetInstance(); ?>
<ul class="crumbs nolstyle llist">
    <li>
        <a href="index.php">Главная</a><span>&rarr;</span>
    </li>
    <li>
        <?=$article['title']?>
    </li>
</ul>

<article class="clearfix">
    <h2><?=$article['title']?></h2>

    <?php if ($mUsers->Can('EDIT_ARTICLE')): ?>
        <div class="tabs">
            <a class="btn" href="index.php?c=articles&act=edit&id=<?=$article['id_article']?>">Редактировать статью</a>
            <a class="btn" href="index.php?c=articles&act=delete&id=<?=$article['id_article']?>">Удалить статью</a>
        </div>
    <?php endif; ?>

    <p><?=nl2br($article['content'])?></p>
</article>


<div id="comments">
    <?=$comments;?>
    <?=$comment_form;?>
</div>
