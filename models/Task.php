<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $idAgent
 * @property integer $idRealty
 * @property integer $status
 * @property string $dateStart
 * @property string $dateEnd
 */

class Task extends \yii\db\ActiveRecord {
	public static function tableName() {
		return 'task';
	}

	public function rules() {
		return [
			[['idAgent', 'idRealty'], 'required'],
			[['idAgent', 'idRealty', 'status'], 'integer'],
		];
	}

	public function attributeLabels() {
		return [
			'id' => 'Ид',
			'idAgent' => 'Агент',
			'idRealty' => 'Задача',
			'status' => 'Статус',
			'dateStart' => 'Дата начала',
			'dateEnd' => 'Дата завершения',
		];
	}

	public function getOwner() {
		return $this->hasOne(Users::className(), ['id' => 'idAgent']);
	}

	public function beforeSave($insert) {
		parent::beforeSave($insert);

		$this->dateStart = date('Y-m-d');
		return true;
	}

    public static function getStatistics($status, $idCategory, $isHot) {
        return self::find()->innerJoin('realty r', 'r.id = idRealty')->where(['status' => $status, 'r.idCategory' => $idCategory, 'isHot' => $isHot, 'forDelete' => 0])->all();
    }

	public static function unCompleteTasks() {
        $key = Cache::buildKey([__METHOD__]);
        $result = Cache::cache()->get($key);

        if ($result == null) {
            $result = Task::findAll(['status' => 0]);
            Cache::cache()->add($key, $result);
        }

	    return $result;
	}

	public static function completeTasks(){
        $key = Cache::buildKey([__METHOD__]);
        $result = Cache::cache()->get($key);

        if ($result == null) {
            $result = Task::findAll(['status' => 1]);
            Cache::cache()->add($key, $result);
        }

        return $result;
	}
}
