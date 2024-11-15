<?php

/** @var yii\web\View $this */

use app\models\Book;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var yii\data\ActiveDataProvider $booksProvider */


$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(); ?>

    <?php if (isset($categoryProvider) && $categoryProvider->getTotalCount() > 0): ?>
        <?= ListView::widget([
            'dataProvider' => $categoryProvider,
            'itemView' => 'categoryCard',
            'layout' => '<div class="d-flex flex-wrap">{items}</div>{pager}',
        ]); ?>
    <?php endif; ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if (isset($booksProvider) && $booksProvider->getTotalCount() > 0): ?>
        <?= ListView::widget([
            'dataProvider' => $booksProvider,
            'itemView' => 'card',
            'layout' => '<div class="d-flex flex-wrap gap-3">{items}</div>{pager}',
        ]); ?>
    <?php endif; ?>



    <?php Pjax::end(); ?>

</div>