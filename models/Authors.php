<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string $title
 */
class Authors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
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
            'title' => 'Title',
        ];
    }

    public static function getAuthors()
    {
        return (new Query())->select('title')->from('authors')->indexBy('id')->column();
    }

    public static function getAuthorsByBookId($id)
    {
        return (new Query())->select('title')->from('authors_match')->leftJoin('authors', 'authors_match.authorsId=authors.id')->where(['authors_match.bookId' => $id])->all();
    }

    public static function getAuthorsId($title)
    {
        return self::findOne(['title' => $title])->id;
    }

    public function getBook()
    {
        return $this->hasMany(Book::class, ['id' => 'bookId'])->viaTable('authors_match', ['authorsId' => 'id']);
    }

    public function getAuthorsMatches()
    {
        return $this->hasMany(AuthorsMatch::class, ['authorsId' => 'id']);
    }
}
