<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newsList".
 *
 * @property integer $id
 * @property integer $idList
 * @property integer $idRealty
 */
class NewsList extends \yii\db\ActiveRecord {
	public static function tableName() {
		return 'newsList';
	}

	public function rules() {
		return [
			[['idList', 'idRealty'], 'required'],
			[['idList', 'idRealty'], 'integer']
		];
	}

	public function attributeLabels() {
		return [
			'id' => 'ID',
			'idList' => 'Id List',
			'idRealty' => 'Id Realty',
		];
	}
}
