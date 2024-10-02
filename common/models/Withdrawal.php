<?php

namespace common\models;

use yii\db\ActiveRecord;

class Withdrawal extends ActiveRecord
{
    public static function tableName()
    {
        return 'withdrawal';
    }

    public function rules()
    {
        return [
            [['user_id', 'amount'], 'required'],
            [['user_id'], 'integer'],
            [['amount'], 'number'],
            [['status'], 'string'],
            [['request_date'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'status' => 'Status',
            'request_date' => 'Request Date',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}