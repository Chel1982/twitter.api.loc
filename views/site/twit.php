<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Twitter Application';
?>

<div class="site-index">
    <div class="row">
        <div class="col-md-4">
            <?php if (isset($twit)): ?>
                <?=  $twit[0]->full_text ?>
            <?php endif; ?>
        </div>
    </div>
</div>
