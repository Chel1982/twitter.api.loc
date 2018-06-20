<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Twitter Application';
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-4">
            <div>
                <?= Html::a('На главную страницу', Url::home()) ?>
            </div>
            <?php if (isset($twusers)): ?>
                <?php foreach ($twusers as $twuser): ?>
                    <?php  $form = ActiveForm::begin(); ?>
                        <ul>
                            <li><?= Html::a(Html::img($twuser->profile_image_url)) ?><?= $twuser->name ?></li>
                            <li><?= 'ID пользователя: ' . $twuser->id ?></li>
                            <li><?= 'Описание: ' . $twuser->description ?></li>
                            <li><?= $twuser->verified ? 'Страница пользователя верифицированна' : 'Страница пользователя не верифицированна' ?></li>
                        </ul>
                    <div class="form-group">
                        <?= Html::a('Добавиться к пользователю в фоловеры', ['site/add-user', 'user_id' => $twuser->id], ['class'=>'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>