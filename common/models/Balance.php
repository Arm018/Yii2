<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "balance".
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property string $updated_at
 * @property User $user
 */
class Balance extends ActiveRecord
{
    public static function tableName()
    {
        return 'balance';
    }

    public function rules()
    {
        return [
            [['user_id', 'amount'], 'required'],
            [['user_id'], 'integer'],
            [['amount'], 'number'],
            [['date'], 'safe'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}