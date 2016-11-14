<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property string $key
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord {
    public static function tableName() {
        return 'settings';
    }

    public function rules() {
        return [
            [['key', 'value'], 'string', 'max' => 255],
            [['key'], 'unique']
        ];
    }

    public function attributeLabels() {
        return [
            'key' => 'Ключ',
            'value' => 'Значение',
        ];
    }
}
