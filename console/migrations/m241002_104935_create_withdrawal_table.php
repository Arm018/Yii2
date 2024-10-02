<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%withdrawal}}`.
 */
class m241002_104935_create_withdrawal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%withdrawal}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'status' => "ENUM('pending', 'approved', 'declined') NOT NULL DEFAULT 'pending'",
            'request_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-withdrawal-user_id',
            '{{%withdrawal}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-withdrawal-user_id', '{{%withdrawal}}');
        $this->dropTable('{{%withdrawal}}');
    }
}
