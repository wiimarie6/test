<?php

use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\Html;
?>

<?= Html::a(Html::encode($model->title), ['site/category', 'category' => $model->id], ['class' => 'btn btn-outline-primary w-100'])?>
