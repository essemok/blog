<?php $mUsers = M_Users::GetInstance();?>
<?php if(sizeof($comments) > 0): ?>
    <h3>Комментарии</h3>

    <ul class="nolstyle">
        <?php foreach ($comments as $comment): ?>
            <li class="comment">
                <div class="c-info">
			        <span class="name">
				        <?=$comment['name']?>
			        </span>
			        <span class="c-date">
                        <?php
                            $str = $comment['c_date'];
                            $str = strtotime($str);
                            echo $str = date('d.m.o - H:i', $str);
                        ?>
			        </span>
                </div>
                <div class="c-content">
                    <p><?=$comment['c_text']?></p>
                    <form method="post">
                        <input type="hidden" name="id_comment" value="<?=$comment['id_comment']?>">
                        <?php if ($mUsers->Can('DELETE_COMMENTS')): ?>
                        <input type="submit" name="c_delete" value="Удалить">
                        <?php endif;?>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
