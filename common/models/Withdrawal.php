<?php

namespace common\models;

use yii\db\ActiveRecord;

class Withdrawal extends ActiveRecord
{
    const STATUS_PENDING = 0;

    const STATUS_SUCCESS = 1;

    const STATUS_DECLINED = 2;

    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SUCCESS => 'Approved',
            self::STATUS_DECLINED => 'Declined',
        ];
    }

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
            [['status'], 'integer'],
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

    public function getStatusName(): string
    {
        $statusLabels = self::getStatusLabels();
        return $statusLabels[$this->status] ?? 'Unknown';
    }


}