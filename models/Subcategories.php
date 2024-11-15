<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subcategories".
 *
 * @property int $id
 * @property string $title
 * @property int $categoriesId
 *
 * @property Categories $categories
 */
class Subcategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subcategories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'categoriesId'], 'required'],
            [['categoriesId'], 'integer'],
            [['title'], 'string', 'max' => 255],
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
            'title' => 'Title',
            'categoriesId' => 'Categories ID',
        ];
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
