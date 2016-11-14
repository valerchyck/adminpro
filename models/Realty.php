<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ColumnSchema;

/**
 * This is the model class for table "realty".
 *
 * @property integer $id
 * @property integer $idCategory
 * @property integer $number
 * @property integer $owner
 * @property integer $code
 * @property string $date
 * @property integer $dateDiff
 * @property string $client
 * @property string $agent
 * @property string $city
 * @property string $area
 * @property string $street
 * @property string $metro
 * @property integer $roomCount
 * @property string $furniture
 * @property string $state
 * @property string $feature
 * @property string $kitchen
 * @property string $limit
 * @property double $fullLandArea
 * @property double $landArea
 * @property integer $floor
 * @property integer $floorCount
 * @property double $price
 * @property integer $fphone
 * @property integer $sphone
 * @property integer $tphone
 * @property integer $frphone
 * @property string $url
 * @property string $text
 * @property string $comment
 * @property string $adminComment
 * @property string $isHot
 * @property string $forDelete
 * @property string $deleteDate
 * @property string $diffDate
 * @property integer $edited
 * @property integer $ready
 */

class Realty extends \yii\db\ActiveRecord {
	public static $correspondence = [
		'№' => 'number',
		'Владелец' => 'owner',
		'Код' => 'code',
		'Клиент' => 'client',
		'Агент' => 'agent',
		'Нас.п.' => 'city',
		'Район' => 'area',
		'Улица' => 'street',
		'Метро' => 'metro',
		'Кол.комн.' => 'roomCount',
		'Мебель' => 'furniture',
		'Тип' => 'roomCount',
		'Вид' => 'state',
		'Хар-ка' => 'feature',
		'Кух' => 'kitchen',
		'Срок' => 'limit',
		'Общ' => 'fullLandArea',
		'Жил' => 'landArea',
		'Пл.уч.' => 'landArea',
		'Эт' => 'floor',
		'Этажн' => 'floorCount',
		'Цена' => 'price',
		'Тел1' => 'fphone',
		'Тел2' => 'sphone',
		'Тел3' => 'tphone',
		'Тел4' => 'frphone',
		'Ссылка' => 'url',
		'Текст' => 'text',
		'Прим.' => 'comment',
		'adm_prim' => 'adminComment',
	];
    const TYPE_PK = 'pk';
    const TYPE_BIGPK = 'bigpk';
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_SMALLINT = 'smallint';
    const TYPE_INTEGER = 'integer';
    const TYPE_BIGINT = 'bigint';
    const TYPE_FLOAT = 'float';
    const TYPE_DOUBLE = 'double';
    const TYPE_DECIMAL = 'decimal';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TIMESTAMP = 'timestamp';
    const TYPE_TIME = 'time';
    const TYPE_DATE = 'date';
    const TYPE_BINARY = 'binary';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_MONEY = 'money';

	public static function tableName() {
		return 'realty';
	}

    public function rules() {
		return [
			[['idCategory'], 'required'],
			[['idCategory', 'dateDiff', 'number', 'owner', 'code', 'roomCount', 'floor',
                'floorCount', 'fphone', 'sphone', 'tphone', 'frphone', 'isHot', 'forDelete', 'edited', 'ready'], 'integer'],
			[['date'], 'safe'],
			[['furniture', 'state', 'feature', 'url', 'text', 'comment', 'adminComment'], 'string'],
			[['fullLandArea', 'landArea', 'price'], 'number'],
			[['client', 'agent', 'city', 'area', 'street', 'metro', 'kitchen', 'limit'], 'string', 'max' => 255]
		];
	}

	public function attributeLabels() {
		return [
			'id' => 'Ид',
			'idCategory' => 'Раздел',
			'number' => 'Номер',
			'owner' => 'Владелец',
			'code' => 'Код',
			'date' => 'Дата',
            'dateDiff' => 'Разница',
			'deleteDate' => 'Дата удаления',
			'client' => 'Клиент',
			'agent' => 'Агент',
			'city' => 'Нас. пункт',
			'area' => 'Район',
			'street' => 'Улица',
			'metro' => 'Метро',
			'roomCount' => 'Комнат',
			'furniture' => 'Мебель',
			'state' => 'Состояние',
			'feature' => 'Характеристика',
			'kitchen' => 'Кухня',
			'limit' => 'Срок',
			'fullLandArea' => 'Общ. пл',
			'landArea' => 'Площадь',
			'floor' => 'Этаж',
			'floorCount' => 'Этажность',
			'price' => 'Цена',
			'fphone' => 'Телефон',
			'sphone' => 'Телефон',
			'tphone' => 'Телефон',
			'frphone' => 'Телефон',
			'url' => 'Ссылка',
			'text' => 'Текст',
			'comment' => 'Комментарий',
			'adminComment' => 'Комментарий админа',
			'isHot' => 'ГС',
			'forDelete' => 'На удаление',
			'edited' => 'Отработано',
			'ready' => 'Готово',
		];
	}

	public static function originName($name) {
		return self::$correspondence[$name];
	}

	public static function getTasks($idAgent, $finish = 0) {
        $key = Cache::buildKey([__METHOD__, $idAgent, $finish]);
        $result = Cache::cache()->get($key);

//        if ($result === false) {
            $result = self::find()->innerJoin('task', 'task.idRealty = realty.id')->where(['realty.hide' => 0, 'task.idAgent' => $idAgent, 'status' => $finish])->leftJoin('category', 'category.id = realty.idCategory');
//            Cache::cache()->add($key, $result, 3600);
//        }

        return $result;
    }

	public function getUser() {
		return Users::find()->innerJoin('task', 'task.idAgent = users.id')->where(['task.idRealty' => $this->id])->one();
	}

	// admin's queries
	public static function getAdminNewsQuery($idCategory, $dateFilter = null) {
        $key = Cache::buildKey([__METHOD__, $idCategory, $dateFilter]);
        $result = Cache::cache()->get($key);

        if ($result === false) {
            $result = self::find()
                ->where(['idCategory' => $idCategory, 'forDelete' => 0, 'isHot' => 1])
                ->leftJoin('task', 'task.idRealty = realty.id')
                ->leftJoin('users', 'users.id = task.idAgent');

            if ($dateFilter !== null)
                $result->andWhere(['dateDiff' => $dateFilter]);

            Cache::cache()->add($key, $result, 3600);
        }

        return $result;
    }

	public static function getAdminBaseQuery($idCategory, $dateFilter = null) {
        $key = Cache::buildKey([__METHOD__, $idCategory, $dateFilter]);
        $result = Cache::cache()->get($key);

        if ($result === false) {
            $result = self::find()
                ->where(['idCategory' => $idCategory, 'isHot' => 0, 'forDelete' => 0])
                ->leftJoin('task', 'task.idRealty = realty.id')
                ->leftJoin('users', 'users.id = task.idAgent');

            if ($dateFilter !== null)
                $result->andWhere(['dateDiff' => $dateFilter]);

            Cache::cache()->add($key, $result, 3600);
        }

        return $result;
    }

	// agent's queries
	public static function getAgentNewsQuery($idCategory, $dateFilter = null) {
        $key = Cache::buildKey([__METHOD__, $idCategory, $dateFilter, 1]);
        $result = Cache::cache()->get($key);

        if ($result === false) {
            $result = self::find()
                ->where(['idCategory' => $idCategory, 'forDelete' => 0, 'hide' => 0, 'isHot' => 1])
                ->andWhere(['not in', 'id', array_keys(ArrayHelper::index(Task::find()->all(), 'idRealty'))]);

            if ($dateFilter !== null)
                $result->andWhere(['dateDiff' => $dateFilter]);

            Cache::cache()->add($key, $result, 3600);
        }

        return $result;
	}

	public static function getAgentBaseQuery($idCategory, $dateFilter = null) {
        $key = Cache::buildKey([__METHOD__, $idCategory, $dateFilter, 0]);
        $result = Cache::cache()->get($key);

        if ($result === false) {
            $result = self::find()
                ->where(['idCategory' => $idCategory, 'forDelete' => 0, 'hide' => 0, 'isHot' => 0])
                ->andWhere(['not in', 'id', array_keys(ArrayHelper::index(Task::find()->all(), 'idRealty'))]);

            if ($dateFilter !== null)
                $result->andWhere(['dateDiff' => $dateFilter]);

            Cache::cache()->add($key, $result, 3600);
        }

        return $result;
    }

	/** relations */
	public function getCategory() {
		return $this->hasOne(Category::className(), ['id' => 'idCategory']);
	}

	public function getNews() {
		return $this->hasMany(NewsList::className(), ['idRealty' => 'id']);
	}

	public function getTask() {
		return $this->hasOne(Task::className(), ['idRealty' => 'id']);
	}

    public function search($query, $params) {
        /**
         * @var ActiveQuery $query
         */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->session->get('page-size') == null ? 20 : \Yii::$app->session->get('page-size'),
            ],
        ]);

        if (isset($params['Realty'])) {
            foreach ($params['Realty'] as $key => $item) {
                if ($item == null || ($attr = self::getTableSchema()->getColumn($key)) === null)
                    continue;

                if (strpos($item, ';') != false) {
                    $words = explode(';', $item);
                    $where = [];
                    foreach ($words as $value) {
                        $value = trim($value);
                        $where[] = "(realty.$key like '$value%')";
                    }
                    $query->andOnCondition(implode(' or ', $where));
                }
                else {
                    if (self::isNumber($attr)) {
						if (strpos($item, '|') != false) {
							$words = explode('|', $item);
							$from = trim($words[0]);
							$to = trim($words[1]);

                            if ($key == 'price') {
                                $from .= '000';
                                $to .= '000';
                            }

							$query->andWhere("realty.$key between $from and $to");
						}
						else {
							$query->andWhere("realty.$key like '$item%'");
						}
                    }
                    else {
                        $query->andWhere("realty.$key like '%$item%'");
                    }
                }
            }
        }

        return $dataProvider;
    }

    public static function getComments($idCategory, $isHot) {
        $key = Cache::buildKey([__METHOD__, $idCategory, $isHot]);
        $result = Cache::cache()->get($key);

        if ($result === false) {
            $result = self::find()->where(['idCategory' => $idCategory, 'isHot' => $isHot, 'forDelete' => 0])->andWhere("comment <> ''")->all();
            Cache::cache()->add($key, $result, 3600);
        }

        return $result;
    }

    public static function isNumber($attribute) {
        /**
         * @var $attribute ColumnSchema
         */
        $ints = [
            self::TYPE_BIGINT,
            self::TYPE_DOUBLE,
            self::TYPE_FLOAT,
            self::TYPE_INTEGER,
            self::TYPE_SMALLINT,
        ];

        return in_array($attribute->type, $ints);
    }

    public static function setLastRecord($idRealty, $isHot = false) {
        $object = Yii::$app->session->get('lastRecord');
        if ($isHot)
            $object['hotList'] = $idRealty;
        else
            $object['base'] = $idRealty;

        Yii::$app->session->set('lastRecord', $object);
    }

	public static function getAreas() {
		$areas = self::find()
			->select('area')
			->where('area is not null')
			->andWhere("area != ''")
			->groupBy('area')
			->all();

		return ArrayHelper::map($areas, 'area', 'area');
	}

	public static function getStreets($area) {
		if ($area == null)
			return [];

		$streets = self::find()
			->select('street')
			->where('street is not null')
			->andWhere("street != ''")
			->andWhere(['area' => $area])
			->groupBy('street')
			->all();

		return ArrayHelper::map($streets, 'street', 'street');
	}

	public static function getMetro($street) {
		if ($street == null)
			return [];

		$metro = self::find()
			->select('metro')
			->where('metro is not null')
			->andWhere("metro != ''")
			->andWhere(['street' => $street])
			->groupBy('metro')
			->all();

		return ArrayHelper::map($metro, 'metro', 'metro');
	}
}
