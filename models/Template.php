<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property string $id
 * @property string $name
 * @property string $text
 */
class Template extends \yii\db\ActiveRecord {
    public static function tableName() {
        return 'template';
    }

    public function rules() {
        return [
            [['name', 'text'], 'required'],
            [['text'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'Ид',
            'name' => 'Название',
            'text' => 'Текст',
        ];
    }

	public static function macros() {
		return Realty::find()->limit(1)->one()->attributeLabels();
	}

	public function generate($records) {
	    $realty = Realty::findAll(['id' => $records]);
		$result = [];

		foreach ($realty as $item) {
			$text = $this->text;
			if (preg_match_all("|\[([^)]+?)\]|", $text, $matches)) {
				foreach ($matches[1] as $column) {
					$value = $item->{$column};
					$text = str_replace("[$column]", $value, $text);
				}
			}

			$result[] = $text;
		}

		return $result;
	}
}
