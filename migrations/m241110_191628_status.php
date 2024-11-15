<?php

use yii\db\Migration;

/**
 * Class m241110_202928_status
 */
class m241110_191628_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241110_202928_status cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        
        $this->createTable('status', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('status');
    }
    
}
