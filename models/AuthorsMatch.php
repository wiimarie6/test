<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authors_match".
 *
 * @property int $id
 * @property int|null $authorsId
 * @property int|null $bookId
 *
 * @property Authors $authors
 * @property Book $book
 */
class AuthorsMatch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors_match';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['authorsId', 'bookId'], 'integer'],
            [['authorsId'], 'exist', 'skipOnError' => true, 'targetClass' => Authors::class, 'targetAttribute' => ['authorsId' => 'id']],
            [['bookId'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['bookId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'authorsId' => 'Authors ID',
            'bookId' => 'Book ID',
        ];
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasOne(Authors::class, ['id' => 'authorsId']);
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'bookId']);
    }
}
