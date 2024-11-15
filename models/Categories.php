<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $title
 *
 * @property Book[] $books
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['parentId'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getBook()
    {
        return $this->hasMany(Book::class, ['id' => 'bookId'])->viaTable('categories_match', ['categoriesId' => 'id']);
    }

    public static function getCategories() 
    {
        return (new Query())->select('title')->from('categories')->indexBy('id')->column();
    }

    public static function getCategoriesByBookId($id)
    {
        return (new Query())
            ->select('categories.title')
            ->from('categories_match')
            ->leftJoin('categories', 'categories_match.categoriesId = categories.id')
            ->where(['categories_match.bookId' => $id])
            ->all();
    }


    public static function getRootCategories()
    {
        return (new Query())->select('title')->from('categories')->indexBy('parentId')->all();
    }

    public static function getCategoryById($id) 
    {
        return self::find()->where(['id' => $id])->title;
    }

    public static function getChildCategories($id)
    {
        return self::find()->where(['parentId' => $id])->all();
    }
}
