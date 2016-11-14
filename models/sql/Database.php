<?php
namespace app\models\sql;

use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class Database
 * @package app\models\sql
 *
 * @property string $name
 * @property Table[] $tables
 * @property array $dump
 */

class Database extends Model {
    public $name;
    public $tables;
    public $onlyStructure = true;

    /**
     * @return Table[]|null
     */
    public function getTables($where = null) {
        $result = self::find()
            ->select('table_name')
            ->from('INFORMATION_SCHEMA.TABLES')
            ->where(['table_schema' => $this->name]);

        if ($where !== null)
            $result->andWhere(['table_name' => $this->tables]);
        $result = $result->all();

        if ($result === null)
            return null;

        $tables = [];
        foreach ($result as $item) {
            $tables[] = new Table([
                'name' => $item['table_name'],
            ]);
        }

        return $tables;
    }

    public function getTableList() {
        $tables = self::find()
            ->select('table_name')
            ->from('INFORMATION_SCHEMA.TABLES')
            ->where(['table_schema' => $this->name])->all();

        return array_keys(ArrayHelper::map($tables, 'table_name', 'table_name'));
    }

    /**
     * @return array
     */
    public function getDump() {
        $this->tables = $this->getTables($this->tables != null ? $this->tables : null);
        if ($this->tables == null) {
            return null;
        }

        $data = '';
        foreach ($this->tables as $table) {
            $data .= "\n
            DROP TABLE IF EXISTS `{$table->name}`;
            \n".\Yii::$app->db->createCommand("show create table `{$this->name}`.`{$table->name}`")->queryOne()['Create Table'].";\n";

            if (!$this->onlyStructure) {
                $inserts = $table->getInsertQuery($this->name);
                if ($inserts != null) {
                    $data .= $inserts;
                }
            }
        }

        return $data;
    }

    /**
     * @param null $dbName
     * @return Database
     */
    public static function findOne($dbName = null) {
        if ($dbName === null)
            $dbName = explode('=', explode(';', \Yii::$app->db->dsn)[1])[1];

        $result = static::find()
            ->select('table_schema')
            ->from('INFORMATION_SCHEMA.TABLES')
            ->where(['table_schema' => $dbName])->one();

        if ($result === null)
            throw new \InvalidArgumentException("database $dbName not found");

        return new static([
            'name' => $dbName,
        ]);
    }

    public function attributeLabels() {
        return [
            'name' => 'Название',
            'onlyStructure' => 'Только структура',
            'tables' => 'Таблицы',
        ];
    }

    public static function find() {
        return new Query();
    }
}
