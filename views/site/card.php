<?php

use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\Html;
?>
<div class="card" style="width: 18rem;">
    <?= Html::img($model->thumbnailUrl)?>
  <div class="card-body">
    <h5 class="card-title"><?=Html::encode($model->title)?></h5>
    <?= Html::a('Подробнее', ['site/view', 'id' => $model->id], ['class' => 'btn btn-outline-primary w-100'])?>
  </div>
</div>