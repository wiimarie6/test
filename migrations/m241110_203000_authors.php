<?php

use yii\db\Migration;

/**
 * Class m241110_203552_authors
 */
class m241110_203000_authors extends Migration
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
        echo "m241110_203552_authors cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('authors', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->unique(),
        ]);
    }

    public function down()
    {
        $this->dropTable('authors');
    }
    
}
