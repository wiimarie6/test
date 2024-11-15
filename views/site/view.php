<?php

use app\models\Authors;
use app\models\Categories;
use app\models\Status;
use PharIo\Manifest\Author;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'thumbnailUrl',
                'format' => 'html',
                'value' => Html::img($model->thumbnailUrl, ['class' => 'img-fluid', 'width' => 300]),
            ],
            'title',
            'isbn',
            'pageCount',
            'publishedDate',
            'shortDescription:ntext',
            'longDescription:ntext',
            [
                'attribute' => 'statusId',
                'value' => Html::encode(Status::getStatusById($model->statusId)),
            ],
            [
                'attribute' => 'authors',
                'value' => function($model) {
                    $authors = Authors::getAuthorsByBookId($model->id);
                    
                    if (!empty($authors)) {
                        $authorNames = array_map(function($author) {
                            return $author['title'];
                        }, $authors);

                        return implode(', ', $authorNames);
                    }
                    
                    return 'Нет авторов';
                },
            ],
            [
                'attribute' => 'categories',
                'value' => function($model) {
                    $categories = Categories::getCategoriesByBookId($model->id);
                    
                    if (!empty($categories)) {
                        $categoriesTitle = array_map(function($category) {
                            return $category['title'];
                        }, $categories);

                        return implode(', ', $categoriesTitle);
                    }
                    
                    return 'Нет авторов';
                },
            ]
        ],
    ]) ?>


</div>
