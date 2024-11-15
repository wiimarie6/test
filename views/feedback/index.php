<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Feedback $model */

$this->title = 'Форма обратной связи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
