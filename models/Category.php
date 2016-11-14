<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $filename
 * @property string $date
 */
class Category extends \yii\db\ActiveRecord {
	public static function tableName() {
		return 'category';
	}

	public function rules() {
		return [
			[['name', 'filename'], 'required'],
			[['name'], 'string', 'max' => 255],
			[['filename'], 'string', 'max' => 150],
		];
	}

	public function attributeLabels() {
		return [
			'id' => 'Ид',
			'name' => 'Раздел',
			'filename' => 'Название файла',
			'date' => 'Дата последнего обновления БД',
		];
	}

	public function getRealty() {
		return $this->hasMany(Realty::className(), ['idCategory' => 'id']);
	}

	public static function getCategoriesMap() {
		return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
	}
}
