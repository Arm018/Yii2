<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_book}}`.
 */
class m241002_093647_create_user_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_book}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'commission' => $this->decimal(10, 2)->defaultValue(0.00),
            'purchase_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-user_book-user_id',
            '{{%user_book}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_book-book_id',
            '{{%user_book}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_book-book_id', '{{%user_book}}');
        $this->dropForeignKey('fk-user_book-user_id', '{{%user_book}}');
        $this->dropTable('{{%user_book}}');
    }
}
