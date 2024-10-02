<?php

use yii\db\Migration;

/**
 * Class m241002_085228_add_referrer_id_to_user_table
 */
class m241002_085228_add_referrer_id_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'referrer_id', $this->integer()->null());

        $this->addForeignKey(
            'fk-user-referrer_id',
            '{{%user}}',
            'referrer_id',
            '{{%user}}',
            'id',
            'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-user-referrer_id', '{{%user}}');
        $this->dropColumn('{{%user}}', 'referrer_id');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241002_085228_add_referrer_id_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
