<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Twitter Application';
?>

<div class="site-index">
    <div class="row">
        <div class="col-md-4">
            <?php if (isset($usersList)): ?>
                <?php foreach ($usersList->users as $userList): ?>

                    <ul>
                        <li>
                            <?= Html::a(Html::img($userList->profile_image_url)) ?><?= $userList->name ?>
                        </li>
                    </ul>

                    <?= Html::a('Получить последний твит', ['site/get-twit', 'idUser' => $userList->id], ['class'=>'btn btn-success']) ?>
                    <?= Html::a('Удалиться из фоловеров', ['site/delete-user', 'idUser' => $userList->id], ['class'=>'btn btn-success']) ?>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
