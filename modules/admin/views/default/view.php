<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Feedback $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения пользователей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="feedback-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            'name',
            'message:ntext',
            'phone',
            'createdAt',
        ],
    ]) ?>

</div>
