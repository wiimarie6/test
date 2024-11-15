<?php

use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Feedback $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="feedback-form">

    <?php $form = ActiveForm::begin(['id' => 'feedback-form']); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textarea() ?>

    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
        'mask' => '+7(999)999-99-99',
    ]) ?>


    <?= $form->field($model, 'reCaptcha')->widget(
    \himiklab\yii2\recaptcha\ReCaptcha3::className(),
    [
        'name' => 'reCaptcha',
        'siteKey' => '6LeTB3oqAAAAAFIJrligI2thMuXw3nR8h-xrNVqB',
        
    ]
)->label(false) ?>

    <!-- <div class="g-recaptcha" data-sitekey="6LeTB3oqAAAAAFIJrligI2thMuXw3nR8h-xrNVqB"></div> -->

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
