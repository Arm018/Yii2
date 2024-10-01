<?php

use yii\db\Migration;

/**
 * Class m241001_140528_add_referral_code_to_user_table
 */
class m241001_140528_add_referral_code_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'referral_code', $this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'referral_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241001_140528_add_referral_code_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
