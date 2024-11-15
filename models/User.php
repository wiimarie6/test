<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $authKey
 * @property int $roleId
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['roleId'], 'integer'],
            [['login', 'authKey'], 'string', 'max' => 255],
            [['login'], 'unique'],
            ['password', 'string', 'min' => 6, 'message' => 'Пароль может содержать минимум 6 символов'],
            ['login', 'match', 'pattern' => "/^[A-Za-z]+$/", 'message' => 'Логин может содержать только символы латиницы']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'password' => 'Пароль',
            'authKey' => 'Auth Key',
            'roleId' => 'Role ID',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $this->authKey = Yii::$app->security->generateRandomString();
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
            $this->roleId = 1;
        }
        return true;
    }

    public function getIsAdmin() 
    {
        return $this->roleId == 2;
    }

    public static function findByLogin($login) 
    {
        return self::findOne(['login' => $login]);
    }

    public function validatePassword($password) 
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}
