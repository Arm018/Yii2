<?php


namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $biography
 *
 * @property Book[] $books
 */
class Author extends ActiveRecord
{
    public static function tableName()
    {
        return 'authors';
    }

    public static function getAuthorList()
    {
            return self::find()
            ->select(['CONCAT(first_name, " ", last_name) AS name', 'id'])
            ->indexBy('id')
            ->column();
    }


    public function rules()
    {
        return [
            [['first_name', 'last_name', 'biography'], 'required'],
            [['biography'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'biography' => 'Biography',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooksAuthors()
    {
        return $this->hasMany(BooksAuthors::class, ['author_id' => 'id']);
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->via('booksAuthors');
    }
}
