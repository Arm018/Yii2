<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 *
 * @property int $id
 * @property int $user_id
 * @property float $total_amount
 * @property float $commission
 * @property string $purchase_date
 */
class Order extends ActiveRecord
{
    public static function tableName()
    {
        return 'orders';
    }

    public function rules()
    {
        return [
            [['user_id', 'total_amount'], 'required'],
            [['user_id'], 'integer'],
            ['total_amount', 'number', 'min' => 0],
            ['commission', 'number', 'min' => 0],
            ['purchase_date', 'safe']
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }
}
