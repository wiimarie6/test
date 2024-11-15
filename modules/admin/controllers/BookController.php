<?php

namespace app\modules\admin\controllers;

use app\models\Authors;
use app\models\AuthorsMatch;
use app\models\Book;
use app\models\BookSearch;
use app\models\Categories;
use app\models\CategoriesMatch;
use app\models\Status;
use PharIo\Manifest\Author;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    public function actions()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAdmin) {
            $this->goBack();
        }
    }
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Book::find();
        $booksProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        $searchModel = new BookSearch();
        $booksProvider = $searchModel->search($this->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'booksProvider' => $booksProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCategoriesChange($parentId)
    {
        $categories = Categories::findAll(['parentId' => $parentId]);
        if (!empty($categories)) {
            foreach ($categories as $category) {
                echo Html::tag('option', Html::encode($category->title), ['value' => $category->id]);
            }
        } else {
            echo Html::tag('option', 'Нет подкатегорий', ['value' => '']);
        }
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Book();
        $model->scenario = Book::SCENARIO_IMAGE;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                $model->publishedDate = Yii::$app->formatter->asDate($model->publishedDate, 'php:Y-m-d H:i:s');
                    if ($model->upload()) {
                        $model->thumbnailUrl = $model->imageFile->name;
                        if ($model->save(false)) {
                            foreach ($model->authors as $key => $value) {
                                $authorsMatch = new AuthorsMatch();
                                $author = Authors::findOne(['title' => $value]);
                                if (!$author) {
                                    $author = new Authors();
                                    $author->title = $value;
                                    $author->save();
                                }
                                $authorsMatch->bookId = $model->id;
                                $authorsMatch->authorsId = $author->id;
                                $authorsMatch->save();
                            }

                            $categoriesMatch = new CategoriesMatch();
                            $category = Categories::findOne(['id' => $model->categories]);
                            if ($category) {
                                $categoriesMatch->categoriesId = $category->id;
                                $categoriesMatch->bookId = $model->id;
                                $categoriesMatch->save();
                            }
                            
                            if ($model->categoriesChild) {
                                $categoriesMatch = new CategoriesMatch();
                                $categoryChild = Categories::findOne(['id' => $model->categoriesChild]);
                                if ($categoryChild) {
                                    $categoriesMatch->categoriesId = $categoryChild->id;
                                    $categoriesMatch->bookId = $model->id;
                                    $categoriesMatch->save();
                                } else {

                                }

                            }
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                        return;
                    }
                        
                    }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
