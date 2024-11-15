<?php

use yii\db\Migration;

/**
 * Class m241110_203300_authors_match
 */
class m241110_203300_authors_match extends Migration
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
        echo "m241110_203300_authors_match cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        
        $this->createTable('authors_match', [
            'id' => $this->primaryKey(),
            'authorsId' => $this->integer(),
            'bookId' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-authors_match-authorsId',
            'authors_match',
            'authorsId',
            'authors',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-authors_match-bookId',
            'authors_match',
            'bookId',
            'book',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-authors_match-bookId',
            'authors_match',
            'bookId',
            'book',
            'id',
            'CASCADE'
        );

        $this->dropForeignKey(
            'fk-authors_match-authorsId',
            'authors_match',
            'authorsId',
            'authors',
            'id',
            'CASCADE'
        );

        $this->dropTable('authors_match');

    }
    
}
