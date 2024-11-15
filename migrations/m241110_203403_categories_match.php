<?php

use yii\db\Migration;

/**
 * Class m241110_203403_categories_match
 */
class m241110_203403_categories_match extends Migration
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
        echo "m241110_203403_categories_match cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        
        $this->createTable('categories_match', [
            'id' => $this->primaryKey(),
            'categoriesId' => $this->integer(),
            'bookId' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-categories_match-bookId',
            'categories_match',
            'bookId',
            'book',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-categories_match-categoriesId',
            'categories_match',
            'categoriesId',
            'categories',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-categories_match-categoriesId',
            'categories_match',
            'categoriesId',
            'category',
            'id',
            'CASCADE'
        );

        $this->dropForeignKey(
            'fk-categories_match-bookId',
            'categories_match',
            'bookId',
            'book',
            'id',
            'CASCADE'
        );

        $this->dropTable('categories_match');

    }
    
}
