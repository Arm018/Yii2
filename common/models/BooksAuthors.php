<?php

namespace common\models;

use yii\db\ActiveRecord;

class BooksAuthors extends ActiveRecord
{
    public static function tableName()
    {
        return 'books_authors';
    }

    public function rules()
    {
        return [
            [['book_id', 'author_id'], 'required'],
            [['book_id', 'author_id'], 'integer'],
        ];
    }

}