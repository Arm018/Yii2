<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 */
class m240819_090300_create_books_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('books_authors', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'PRIMARY KEY(book_id, author_id)',
        ]);

        // Create index for columns
        $this->createIndex(
            'idx-book_author-book_id',
            'books_authors',
            'book_id'
        );
        $this->createIndex(
            'idx-book_author-author_id',
            'books_authors',
            'author_id'
        );

        // Add foreign keys
        $this->addForeignKey(
            'fk-book_author-book_id',
            'books_authors',
            'book_id',
            'books',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-book_author-author_id',
            'books_authors',
            'author_id',
            'authors',
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
            'books_authors'
        );
        $this->dropForeignKey(
            'fk-book_author-author_id',
            'books_authors'
        );

        $this->dropIndex(
            'idx-book_author-book_id',
            'books_authors'
        );
        $this->dropIndex(
            'idx-book_author-author_id',
            'books_authors'
        );

        $this->dropTable('books_authors');
    }
}
