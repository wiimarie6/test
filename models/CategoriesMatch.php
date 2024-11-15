<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories_match".
 *
 * @property int $id
 * @property int|null $categoriesId
 * @property int|null $bookId
 *
 * @property Book $book
 * @property Categories $categories
 */
class CategoriesMatch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories_match';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoriesId', 'bookId'], 'integer'],
            [['bookId'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['bookId' => 'id']],
            [['categoriesId'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['categoriesId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoriesId' => 'Categories ID',
            'bookId' => 'Book ID',
        ];
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

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::class, ['id' => 'categoriesId']);
    }
}
