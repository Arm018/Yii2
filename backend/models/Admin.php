<?php

namespace backend\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use Yii;

class Admin extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return '{{%admin}}';
    }

    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['password_hash'], 'string', 'max' => 60],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // Adjust if you want to implement this
    }

    public function validateAuthKey($authKey)
    {
        return false; // Adjust if you want to implement this
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}
