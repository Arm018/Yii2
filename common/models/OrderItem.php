<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_items".
 *
 * @property int $id
 * @property int $order_id
 * @property int $book_id
 * @property int $quantity
 * @property float $amount
 * @property float $commission
 */
class OrderItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'order_items';
    }

    public function rules()
    {
        return [
            [['order_id', 'book_id', 'quantity', 'amount'], 'required'],
            [['order_id', 'book_id', 'quantity'], 'integer'],
            ['amount', 'number', 'min' => 0],
            ['commission', 'number', 'min' => 0],
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
}
