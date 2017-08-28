<?php
$mUsers = M_Users::GetInstance();
?>

<ul class="crumbs nolstyle llist">
    <li>
        <a href="index.php">Главная</a><span>&rarr;</span>
    </li>
    <li>
        Пользователи сайта
    </li>
</ul>

<h2>Пользователи сайта</h2>
<ul class="users">
    <?php foreach ($users as $user): ?>
        <li>
            <a href="index.php?c=users&act=read&id=<?=$user['id_user']?>"><?=$user['login']?></a>
            <?php if($mUsers->IsOnline($user['id_user']) == true): ?>
                <span class="online">
				Online
			</span>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
