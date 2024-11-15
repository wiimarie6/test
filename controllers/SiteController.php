<?php

namespace app\controllers;

use app\models\Authors;
use app\models\Book;
use app\models\BookSearch;
use app\models\Categories;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionRegistr()
{
    $model = new \app\models\User();

    if ($model->load(Yii::$app->request->post())) {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->validate()) {
            if ($model->save()) {
                if (Yii::$app->user->login($model)) {
                    Yii::$app->session->setFlash('succes', 'Вы успешно зарегистрировались');
                    return $this->redirect('index');
                }
            }
            return;
        }
    }

    return $this->render('registr', [
        'model' => $model,
    ]);
}

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
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

        $query = Categories::find();

        $categoryProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $this->render('index', [
            'categoryProvider' => $categoryProvider,
            'searchModel' => $searchModel,
           //'dataProvider' => $dataProvider,
            'booksProvider' => $booksProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $authorsModel = Authors::getAuthorsByBookId($model->id);
        return $this->render('view', [
            'authorsModel' => $authorsModel,
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCategory($category)
    {

        // if ($query = Categories::find()->where(['parentId' => $category])) {
        //     $categoryProvider = new ActiveDataProvider([
        //     'query' => $query,
        //     ]);
        // } else {
        //     $query = Book::find()->joinWith('categories')->where(['categories.id' => $category]);
        //     $booksProvider = new ActiveDataProvider([
        //         'query' => $query,
        //         'pagination' => [
        //             'pageSize' => 30,
        //         ],
        //     ]);
        // }

        $searchModel = new BookSearch();
        $booksProvider = $searchModel->search($this->request->queryParams);

        $query = Categories::find()->where(['parentId' => $category]);

        if ($query->exists()) {
            $categoryProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            return $this->render('index', [
                'categoryProvider' => $categoryProvider,
                'searchModel' => $searchModel,
            ]);
        } else {
            $query = Book::find()->innerJoinWith('categories')->where(['categories.id' => $category]);
            $booksProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]);

            return $this->render('index', [
                'searchModel' => $searchModel,
               //'dataProvider' => $dataProvider,
                'booksProvider' => $booksProvider,
            ]);
        }



        
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
