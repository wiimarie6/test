<?php

use yii\db\Migration;

/**
 * Class m241110_191731_book
 */
class m241110_191731_book extends Migration
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
        echo "m241110_191731_book cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'isbn' => $this->string(),
            'pageCount' => $this->integer(),
            'publishedDate' => $this->timestamp(),
            'thumbnailUrl' => $this->string(),
            'shortDescription' => $this->text()->null(),
            'longDescription' => $this->text()->null(),
            'statusId' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-book-statusId',
            'book',
            'statusId',
            'status',
            'id',
            'CASCADE'
        );

    }

    public function down()
    {

        $this->dropForeignKey(
            'fk-book-statusId',
            'book',
            'statusId',
            'status',
            'id',
            'CASCADE'
        );
        $this->dropTable('book');

    }
    
}
