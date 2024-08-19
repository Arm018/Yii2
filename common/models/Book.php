<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $publication_year
 *
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    public $authorIds = [];

    public static function tableName()
    {
        return '{{%books}}';
    }

    public function rules()
    {
        return [
            [['title', 'description', 'publication_year'], 'required'],
            [['description'], 'string'],
            [['publication_year'], 'integer'],
            [['title'], 'string', 'max' => 255],
            ['authorIds', 'each', 'rule' => ['integer']],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'publication_year' => 'Publication Year',
            'author_ids' => 'Authors',

        ];
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooksAuthors()
    {
        return $this->hasMany(BooksAuthors::class, ['book_id' => 'id']);
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->via('booksAuthors');
    }

}
