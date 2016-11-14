<?php

namespace app\models;

use yii\base\Model;

class Xls extends Model {
    public $file;
    public $category;

    public function attributeLabels() {
        return [
            'file' => 'Загрузите XLS-файл',
            'category' => 'Раздел БД "АГЕНТПРО"',
        ];
    }
}
