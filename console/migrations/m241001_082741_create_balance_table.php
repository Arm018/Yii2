<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%balance}}`.
 */
class m241001_082741_create_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('balance', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex(
            'idx-balance-user_id',
            'balance',
            'user_id'
        );

        $this->addForeignKey(
            'fk-balance-user_id',
            'balance',
            'user_id',
            'user',
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
            'fk-balance-user_id',
            'balance'
        );

        $this->dropIndex(
            'idx-balance-user_id',
            'balance'
        );

        $this->dropTable('balance');
    }
}
