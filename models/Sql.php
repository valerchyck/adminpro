<?php

namespace app\models;

use app\models\sql\Database;
use app\models\sql\Table;
use yii\base\Model;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Query;

/**
 * Class Sql
 * @package app\models
 *
 * @property string $query
 * @property Connection $db
 */

class Sql extends Model {
    public $query;
    public $db;
    public $result = null;

    public function init() {
        parent::init();

        $this->db = \Yii::$app->db;
    }

    public function rules() {
        return [
            [['query'], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'query' => 'Запрос',
        ];
    }

    /**
     * @param $query
     * @return array|\Exception|Exception
     */
    public function execute() {
        try {
            return $this->db->createCommand($this->query)->queryAll();
        }
        catch (Exception $e) {
            return $e;
        }
    }
}
