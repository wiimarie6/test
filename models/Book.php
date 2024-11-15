<?php

namespace app\models;

use Codeception\Scenario;
use Yii;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property string $isbn
 * @property int $pageCount
 * @property string $publishedDate
 * @property string $thumbnailUrl
 * @property string $shortDescription
 * @property string $longDescription
 * @property int $statusId
 * @property int $authorsId
 * @property int $categoriesId
 */
class Book extends \yii\db\ActiveRecord
{
    public $imageFile;
    public $dataArray;
    public $categories;
    public $categoriesChild;
    public $authors;

    const SCENARIO_IMAGE = 'image';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'isbn'], 'required'],
            [['authors', 'categories'], 'safe'],
            [['id', 'pageCount', 'statusId', 'categories', 'categoriesChild'], 'integer'],
            [['statusId'], 'exist', 'skipOnError' => false, 'targetClass' => Status::class, 'targetAttribute' => ['statusId' => 'id']],
            ['publishedDate', 'datetime', 'format' => 'php: Y-m-d H:i:s'],
            [['shortDescription', 'longDescription'], 'string'],
            [['title', 'isbn', 'thumbnailUrl'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'on' => self::SCENARIO_IMAGE],
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
            'isbn' => 'Артикул',
            'pageCount' => 'Количество страниц',
            'publishedDate' => 'Дата публикации',
            'thumbnailUrl' => 'Изображение',
            'shortDescription' => 'Описание',
            'longDescription' => 'Содержание',
            'statusId' => 'Статус',
            'authors' => 'Авторы',
            'categories' => 'Категории',
            'categoriesChild' => 'Подкатегории',
            'imageFile' => 'Обложка книги',
            'authorsTitle' => 'Авторы',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $fileName = Yii::$app->security->generateRandomString() . '.' . $this->imageFile->extension;
            $fileLocation = 'uploads/' . $fileName;
            $this->imageFile->saveAs($fileLocation);

            $baseUrl = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl;

            $this->imageFile->name = $baseUrl . '/' . $fileLocation;

            return true;
        } else {
            return false;
        }
    }


    public function getAuthors()
    {
        return $this->hasMany(Authors::class, ['id' => 'authorsId'])->viaTable('authors_match', ['bookId' => 'id']);
    }

    /**
     * Gets query for [[AuthorsMatches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorsMatches()
    {
        return $this->hasMany(AuthorsMatch::class, ['bookId' => 'id']);
    }

    /**
     * Gets query for [[CategoriesMatches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesMatches()
    {
        return $this->hasMany(CategoriesMatch::class, ['bookId' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'statusId']);
    }

    public function getCategories()
    {
        return $this->hasMany(Categories::class, ['id' => 'categoriesId'])->viaTable('categories_match', ['bookId' => 'id']);
    }

    
}
