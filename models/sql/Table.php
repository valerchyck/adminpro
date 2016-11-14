<?php
namespace app\models\sql;

use yii\base\Model;
use yii\db\Query;

/**
 * Class Table
 * @package app\models\sql
 *
 * @property string $name
 * @property array $columns
 */

class Table extends Model {
    public $name;
    public $columns;
    public $dbName;

    public function getInsertQuery($dbName) {
        $records = self::find()->from("$dbName.{$this->name}")->all();
        if ($records == null)
            return null;

        $result = [];
        $columns = array_keys($records[0]);
        foreach ($records as $record) {
            $result[] = array_values($record);
        }

        return self::find()->createCommand()->batchInsert("{$this->name}", $columns, $result)->rawSql.";\n";
    }

    /**
     * @return Query
     */
    public static function find() {
        return new Query();
    }
}
