<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace yii\web;

use app\models\Authors;
use app\models\Book;
use app\models\Categories;
use app\models\Status;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Parses a raw HTTP request using [[\yii\helpers\Json::decode()]].
 *
 * To enable parsing for JSON requests you can configure [[Request::parsers]] using this class:
 *
 * ```php
 * 'request' => [
 *     'parsers' => [
 *         'application/json' => 'yii\web\JsonParser',
 *     ]
 * ]
 * ```
 *
 * @author Dan Schmidt <danschmidt5189@gmail.com>
 * @since 2.0
 */
class JsonParser implements RequestParserInterface
{
    /**
     * @var bool whether to return objects in terms of associative arrays.
     */
    public $asArray = true;
    /**
     * @var bool whether to throw a [[BadRequestHttpException]] if the body is invalid JSON
     */
    public $throwException = true;

    public $fileUrl;

    public function parse($rawBody, $contentType)
    {
        $rawBody = file_get_contents('https://gitlab.grokhotov.ru/hr/yii-test-vacancy/-/blob/master/books.json');
        // converts JSONP to JSON
        if (strpos($contentType, 'application/javascript') !== false) {
            $rawBody = preg_filter('/(^[^{]+|[^}]+$)/', '', $rawBody);
        }

        try {
            $parameters = Json::decode($rawBody, $this->asArray);
            return $parameters === null ? [] : $parameters;
        } catch (InvalidArgumentException $e) {
            if ($this->throwException) {
                throw new BadRequestHttpException('Invalid JSON data in request body: ' . $e->getMessage());
            }

            return [];
        }
    }


    public function saveToDatabase($data)
    {
        foreach ($data as $bookItem) {

            $status = Status::findOne(['title' => $bookItem['status']]);
            if (!$status) {
                $status = new Status();
                $status->title=$bookItem['status'];
                $status->save();
            }
            $bookModel = new Book();
            $bookModel->load($bookItem);
            $bookModel->statusId=$status->id;
            $bookModel->publishedDate= $bookItem['publishedDate']['$date'];
            $bookModel->save();

            $categories = ArrayHelper::getValue($bookItem, 'categories');

            $categoryParentId=0;
                foreach ($categories as $key=>$category ) {
                    $categoryModel = new Categories();
                    $categoryModel->title=$category;

                if ($key == 0) {
                    $categoryModel->save();
                    $categoryParentId=$categoryModel->id;                    
                } else {
                    $categoryModel->parentId=$categoryParentId;
                    $categoryModel->save();
                    // $subcategories = new Subcategories();
                    // $subcategories->title=$category;
                    // $subcategories->categoriesId = $categoryModel->id;
                    // $subcategories->save();
            }
            }

            $authors = ArrayHelper::getValue($bookItem, 'authors');

            $authorModel = new Authors();

                foreach ($authors as $key => $author) {
                    $authorModel->title=$author;
                    $authorModel->save();
                }
            }
        }
}