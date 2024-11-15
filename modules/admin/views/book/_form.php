<?php

use app\models\Categories;
use app\models\Status;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pageCount')->textInput() ?>

    <?= $form->field($model, 'publishedDate')->textInput(['type' => 'datetime-local']) ?>

    <?= $form->field($model, 'shortDescription')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'longDescription')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'statusId')->dropDownList(Status::getStatuses(), ['prompt' => 'Выберите статус...']) ?>

    <?= $form->field($model, 'authors')->widget(MultipleInput::className(), [
            'min'               => 1, // should be at least 1 rows
            'allowEmptyList'    => false,
            'enableGuessTitle'  => true,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            'iconSource' => MultipleInput::ICONS_SOURCE_FONTAWESOME // show add button in the header
    ])
    ->label(false);?>

    <?= $form->field($model, 'categories')->dropDownList(Categories::getCategories(), [
                'prompt' => 'Выберите категорию...',
                'onchange' => '$.get("' . Url::to(['book/categories-change']) . '?parentId=" + $(this).val(), function(data) {
                    $("select#categoriesChildId").html(data);
                    });'
            ])?>

    <?= $form->field($model, 'categoriesChild')->dropDownList([], ['id' => 'categoriesChildId', 'prompt' => 'Выберите подкатегорию...']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
