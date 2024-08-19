<?php

use yii\db\Migration;

/**
 * Class m240819_073224_insert_initial_admin
 */
class m240819_073224_insert_initial_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%admins}}', [
            'username' => 'admin',
            'password_hash' => Yii::$app->security->generatePasswordHash('password'),
            'auth_key' => Yii::$app->security->generateRandomString(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%admins}}', ['username' => 'admin']);

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240819_073224_insert_initial_admin cannot be reverted.\n";

        return false;
    }
    */
}
