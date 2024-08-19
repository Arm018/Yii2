<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 */
class m240819_090300_create_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book_author', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'PRIMARY KEY(book_id, author_id)',
        ]);

        // Create index for columns
        $this->createIndex(
            'idx-book_author-book_id',
            'book_author',
            'book_id'
        );
        $this->createIndex(
            'idx-book_author-author_id',
            'book_author',
            'author_id'
        );

        // Add foreign keys
        $this->addForeignKey(
            'fk-book_author-book_id',
            'book_author',
            'book_id',
            'book',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-book_author-author_id',
            'book_author',
            'author_id',
            'author',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-book_author-book_id',
            'book_author'
        );
        $this->dropForeignKey(
            'fk-book_author-author_id',
            'book_author'
        );

        $this->dropIndex(
            'idx-book_author-book_id',
            'book_author'
        );
        $this->dropIndex(
            'idx-book_author-author_id',
            'book_author'
        );

        $this->dropTable('book_author');
    }
}
