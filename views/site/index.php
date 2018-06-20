<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Twitter Application';
?>

<div class="site-index">
    <div class="row">
        <div class="col-md-4"
            <?php Pjax::begin(); ?>
            <?php  $form = ActiveForm::begin(); ?>

                <?= $form->field($modelSearch, 'usersearch')->textInput(['placeholder' => 'Введите имя для поиска', 'class'=>'form-control', 'requred']); ?>

            <div class="form-group">
                <?= Html::submitButton('Поиск пользователей Twitter\'a', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>
    </div>
    <div class="col-md-4">
        <?php if (isset($userAdd)): ?>
            <div>
                <p>
                    Вы добавлены к <b> <?= $userAdd->name ?> </b> в фоловеры
                </p>
            </div>

        <?php endif; ?>
        <?php if (isset($userDelete)): ?>
            <div>
                <p>
                    Вы удалились из фоловеров <b> <?= $userDelete->name ?> </b>
                </p>
            </div>

        <?php endif; ?>
    </div>
</div>
