<?php

use yii\db\Migration;

/**
 * Class m241110_203500_categories
 */
class m241110_203100_categories extends Migration
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
        echo "m241110_203500_categories cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->unique(),
            'parentId' => $this->integer()->null(),
        ]);
    }

    public function down()
    {
        $this->dropTable('categories');
    }
    
}
