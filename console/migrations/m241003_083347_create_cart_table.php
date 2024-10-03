<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cart}}`.
 */
class m241003_083347_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cart}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-cart-user_id', '{{%cart}}', 'user_id');
        $this->addForeignKey(
            'fk-cart-user_id',
            '{{%cart}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
        $this->createIndex('idx-cart-book_id', '{{%cart}}', 'book_id');
        $this->addForeignKey(
            'fk-cart-book_id',
            '{{%cart}}',
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
        $this->dropForeignKey('fk-cart-user_id', '{{%cart}}');
        $this->dropForeignKey('fk-cart-book_id', '{{%cart}}');
        $this->dropIndex('idx-cart-user_id', '{{%cart}}');
        $this->dropIndex('idx-cart-book_id', '{{%cart}}');

        $this->dropTable('{{%cart}}');
    }
}
