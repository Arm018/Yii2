<?php

namespace common\models;

use yii\db\ActiveRecord;


/**
 * This is the model class for table "user_book".
 *
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property float $amount
 * @property string $purchase_date
 */
class UserBook extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_book';
    }

    public function rules()
    {
        return [
            [['user_id', 'book_id'], 'required'],
            [['user_id', 'book_id'], 'integer'],
            ['amount', 'number', 'min' => 0],
            ['purchase_date', 'safe']
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
}