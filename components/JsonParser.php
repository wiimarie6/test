<?php

namespace app\components;

use app\models\Authors;
use app\models\AuthorsMatch;
use app\models\Book;
use app\models\Categories;
use app\models\CategoriesMatch;
use app\models\Status;
use app\models\Subcategories;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class JsonParser extends Component
{
    public $fileUrl;

    public function setFilePath($filePath) {
        if (empty($filePath) || !file_exists($filePath)) {
            throw new \Exception("Некорректный или пустой путь к файлу.");
        }
        $this->fileUrl = $filePath;
    }

    public function setFileUrl($fileUrl) {
        if (empty($fileUrl)) {
            throw new \Exception("Пустой URL или путь к файлу.");
        }
    
        if (filter_var($fileUrl, FILTER_VALIDATE_URL)) {
            // Это URL
            $this->fileUrl = $fileUrl;
        } elseif (file_exists($fileUrl)) {
            // Это локальный путь к файлу
            $this->fileUrl = $fileUrl;
        } else {
            throw new \Exception("Некорректный URL или путь к файлу.");
        }
    }

    public function parse()
    {
        
        if (!file_exists($this->fileUrl)) {
            throw new \Exception("Файл не найден: " . $this->fileUrl);
        }

        //$jsonContent = file_get_contents($this->fileUrl);
        $jsonContent = file_get_contents($this->fileUrl);
        //$data = \yii\helpers\Json::decode($jsonContent);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Ошибка при декодировании JSON: " . json_last_error_msg());
        }

        return $data;

        // if (empty($this->fileUrl)) {
        //     throw new \Exception("URL не задан.");
        // }
    
        // if (!filter_var($this->fileUrl, FILTER_VALIDATE_URL)) {
        //     throw new \Exception("Некорректный URL: " . $this->fileUrl);
        // }
    
        // $jsonContent = @file_get_contents($this->fileUrl); 
    
        // if ($jsonContent === false) {
        //     throw new \Exception("Не удалось получить содержимое по URL: " . $this->fileUrl);
        // }
    
        // $data = json_decode($jsonContent, true);
    
        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     throw new \Exception("Ошибка при декодировании JSON: " . json_last_error_msg());
        // }
    
        // return $data;
    }

    public function saveToDatabase($data)
    {
        foreach ($data as $bookItem) {

            $status = Status::findOne(['title' => $bookItem['status']]);
            
            if (!$status) {
                $status = new Status();
                $status->title = $bookItem['status'];
                $status->save();
            }

            $bookModel = new Book();
            $bookModel->load($bookItem, '');
            
            
            $bookModel->statusId = $status->id;

            

            $publishedDate = ArrayHelper::getValue($bookItem, 'publishedDate.$date');

            if ($publishedDate) {
                $bookModel->publishedDate = Yii::$app->formatter->asDatetime($publishedDate, 'php:Y-m-d H:i:s');
            }

            if ($bookModel->save()) {
                $categories = ArrayHelper::getValue($bookItem, 'categories');
                $categoryParentId = 0;
        
                foreach ($categories as $key => $category) {
                    $categoryModel = Categories::findOne(['title' => $category]);
                    if (!$categoryModel) {
                        $categoryModel = new Categories();
                        $categoryModel->title = $category;
                        $categoryModel->save();
                    }
        
                    $categoriesMatch = new CategoriesMatch();
                    $categoriesMatch->bookId = $bookModel->id;
                    $categoriesMatch->categoriesId = $categoryModel->id;
                    $categoriesMatch->save();
        
                    if ($key == 0) {
                        $categoryParentId = $categoryModel->id;
                    } else {
                        $categoryModel->parentId = $categoryParentId;
                        $categoryModel->save();
                    }
                }
        
                $authors = ArrayHelper::getValue($bookItem, 'authors');
                foreach ($authors as $author) {
                    $authorModel = Authors::findOne(['title' => $author]);
                    if (!$authorModel) {
                        $authorModel = new Authors();
                        $authorModel->title = $author;
                        $authorModel->save();
                    }
        
                    $authorMatch = new AuthorsMatch();
                    $authorMatch->bookId = $bookModel->id;
                    $authorMatch->authorsId = $authorModel->id;
                    $authorMatch->save();
                }
                
            }
            }
            // die;          
        }
    }