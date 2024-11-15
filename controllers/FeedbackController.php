<?php

namespace app\controllers;

use app\models\Feedback;
use app\models\FeedbackSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FeedbackController implements the CRUD actions for Feedback model.
 */
class FeedbackController extends Controller
{
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
     * Lists all Feedback models.
     *
     * @return string
     */


    /**
     * Creates a new Feedback model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model = new Feedback();
        if ($this->request->isPost) {
        //     $recaptchaResponse = Yii::$app->request->post('g-recaptcha-response');
        //  $secretKey = '6LeTB3oqAAAAAJ4AcNcpjwstwP8J_j_d7WLX3AGF';
         
        //  $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
        //  $responseKeys = json_decode($response, true);
         
        //  if (!$responseKeys["success"]) {
        //      // Обработка ошибки
        //      return "Ошибка подтверждения reCAPTCHA.";
        //  } else {
        //      // Успешная проверка
        //      return "reCAPTCHA успешно подтверждена.";
        //  }
            if ($model->load($this->request->post()) && $model->save()) {
                
                Yii::$app->mailer->compose('message')
                    ->setFrom(env('YANDEX_LOGIN'))
                    ->setTo($model->email)
                    ->setSubject('Сообщение доставлено')
                    ->send();
                Yii::$app->session->setFlash('success', "Сообщение отправлено");
                return $this->refresh();

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }


    /**
     * Finds the Feedback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Feedback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feedback::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
