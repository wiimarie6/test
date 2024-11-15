<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $message
 * @property string $phone
 * @property string $createdAt
 */
class Feedback extends \yii\db\ActiveRecord
{
    public $reCaptcha;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'message'], 'required'],
            [['createdAt'], 'safe'],
            [['email', 'name', 'phone'], 'string', 'max' => 255],
            [['message'], 'string'],
            [['email'], 'email'],
            ['phone', 'match', 'pattern' => '/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/', 'message' => 'Телефон в формате +7(999)-999-99-99'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator3::className(),
            'secret' => '6LeTB3oqAAAAAJ4AcNcpjwstwP8J_j_d7WLX3AGF', // unnecessary if reСaptcha is already configured
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Имя',
            'message' => 'Сообщение',
            'phone' => 'Телефон',
            'reCaptcha' => 'reCaptcha',
            'createdAt' => 'Created At',
        ];
    }
}
