<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "listRealty".
 *
 * @property integer $id
 * @property string $name
 * @property string $comment
 * @property string $date
 */
class ListRealty extends \yii\db\ActiveRecord {
	public static function tableName() {
		return 'listRealty';
	}

	public function rules() {
		return [
			[['name'], 'required'],
			[['comment'], 'string'],
			[['name'], 'string', 'max' => 255]
		];
	}

	public function attributeLabels() {
		return [
			'id' => 'Ид',
			'name' => 'Название',
			'comment' => 'Комментарий',
		];
	}
}
