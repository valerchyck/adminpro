<?php

namespace app\models;

use Yii;
use app\widgets\account\Account;
use yii\helpers\Url;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property integer $role
 * @property string $login
 * @property string $password
 * @property string $name
 * @property integer $phone
 * @property string $email
 * @property string $skype
 * @property integer $icq
 * @property string $aol
 * @property string $web
 * @property string $social
 * @property integer $birthDay
 * @property integer $birthMonth
 * @property integer $birthYear
 * @property string $city
 * @property string $area
 * @property string $street
 * @property string $metro
 * @property string $idCode
 * @property string $passport
 * @property string $contract
 * @property string $repeatPassword
 * @property string $categories
 * @property string $passwordText
 * @property integer $notDelete
 * @property integer $dateFilter
 * @property integer $clientInfo
 * @property integer $dopInfo
 * @property integer $notice
 * @property integer $addingRecord
 * @property integer $inWork
 * @property Order[] $orders
 */

class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {
	public $authKey;
	public $accessToken;
	public $repeatPassword;

	private $_user = false;

	public static function tableName() {
		return 'users';
	}

	public function rules() {
		return [
			[['name', 'role', 'login', 'password'], 'required'],
			[['phone', 'icq', 'birthDay', 'birthMonth', 'birthYear', 'role', 'notDelete', 'dateFilter',
				'clientInfo', 'dopInfo', 'addingRecord', 'inWork'], 'integer'],
			[['name', 'email', 'skype', 'aol', 'web', 'social', 'city', 'area', 'street', 'metro', 'idCode', 'passport', 'contract', 'login'], 'string', 'max' => 255],
			[['password', 'passwordText'], 'string', 'max' => 100],
			['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
		];
	}

	public function attributeLabels() {
		return [
			'id' => 'Ид',
			'name' => 'ФИО',
			'phone' => 'Телефон',
			'email' => 'E-Mail',
			'skype' => 'Skype',
			'icq' => 'Icq',
			'aol' => 'Aol',
			'web' => 'Веб-сайт',
            'social' => 'Соц. сеть',
			'birthDay' => 'День',
			'birthMonth' => 'Месяц',
			'birthYear' => 'Год',
			'city' => 'Город',
			'area' => 'Район',
			'street' => 'Улица',
			'metro' => 'Метро',
			'idCode' => 'Id Code',
			'passport' => 'Паспорт',
			'contract' => 'Contract',
			'role' => 'Роль',
			'login' => 'Логин',
			'password' => 'Пароль',
			'repeatPassword' => 'Повторите пароль',
			'categories' => 'Разделы',
			'passwordText' => 'Пароль',
			'notDelete' => 'Запрет удаления',
			'dateFilter' => 'Фильтр по датам',
			'clientInfo' => 'Информация о клиентах',
			'dopInfo' => 'Дополнительная информация',
			'addingRecord' => 'Добавление записей',
			'inWork' => 'Статус',
		];
	}

	public function login() {
		if ($this->validate() && $this->getUser() !== false) {
			return Yii::$app->user->login($this->getUser(), 3600*24*30);
		} else {
			return false;
		}
	}

	public function validatePassword($password) {
		return $this->password === md5($password);
	}

	public static function findIdentity($id) {
        $user = self::find()->where(['id'=>$id])->asArray()->all();
		return !Helper::isEmpty($user) ? new static($user[0]) : null;
	}

	public static function findIdentityByAccessToken($token, $type = NULL) {
		$user = self::findOne(['token'=>$token])->toArray();
		if ($user['accessToken'] === $token) {
			return new static($user);
		}

		return null;
	}

	public static function findByUsername($username) {
		$user = self::findOne(['login'=>$username])->toArray();
		if (strcasecmp($user['login'], $username) === 0) {
			return new static($user);
		}

		return null;
	}

	public function getId() {
		return $this->id;
	}

	public function getAuthKey() {
		return $this->authKey;
	}

	public function validateAuthKey($authKey) {
		return $this->authKey === $authKey;
	}

	public function getUser() {
		if ($this->_user === false) {
			$this->_user = self::findOne(['login'=>$this->login]);
		}

		return $this->_user;
	}

    public function getAllTasks() {
        return $this->hasMany(Realty::className(), ['id' => 'idRealty'])->viaTable('task', ['idAgent' => 'id']);
    }

	public function getTasks($idCategory, $isFinish, $isHot, $date = null) {
		$andWhere = [];
		if ($date !== null) {
			switch ($date) {
				case 1:
					$andWhere = 'DATEDIFF(NOW(), task.dateEnd) < 1 and TIME_TO_SEC(TIMEDIFF(NOW(), dateEnd))/3600 < 24';
					break;
				case 2:
					$andWhere = 'DATEDIFF(NOW(), dateEnd) <= 7';
					break;
				case 3:
					$andWhere = 'DATEDIFF(NOW(), dateEnd) <= 31';
					break;
			}
		}

		$where = [
			'realty.idCategory' => $idCategory,
			'task.status' => $isFinish,
			'users.id' => $this->id,
		];
		if ($isHot !== null)
			$where['realty.isHot'] = $isHot;

		return Realty::find()->innerJoin('task', 'realty.id = task.idRealty')->innerJoin('users', 'users.id = task.idAgent')->where($where)->andWhere($andWhere);
	}

	public static function getMenu($role) {
		$menu = [];
		switch ($role) {
			case 1:
				$menu = [
                    [
                        'label' => 'База',
                        'items' => [
                            [
                                'label' => 'Загрузка XLS',
                                'url' => ['/admin/load-xls'],
                                'active' => Yii::$app->controller->route == 'admin/load-xls' ? 1 : 0,
                            ],
                            [
                                'label' => 'Новые записи',
                                'url' => [isset($_SESSION['newsSort']) ? '/admin/news?sort='.$_SESSION['newsSort'] : '/admin/news'],
                                'active' => Yii::$app->controller->action->id == 'news' ? 1 : 0,
                                'options' => [
                                    'class' => 'spec',
                                ],
                            ],
                            [
                                'label' => 'Общая база',
                                'url' => [isset($_SESSION['baseSort']) ? '/admin/hot-list?sort='.$_SESSION['baseSort'] : '/admin/hot-list'],
                                'active' => Yii::$app->controller->action->id == 'hot-list' ? 1 : 0,
                            ],
                            [
                                'label' => 'Агенты',
                                'url' => Url::to(['agent/index']),
                                'active' => Yii::$app->controller->route == 'admin/agents' ? 1 : 0,
                            ],
	                        [
		                        'label' => 'Эксклюзивы',
		                        'url' => Url::to(['custom/index']),
		                        'active' => Yii::$app->controller->route == 'admin/agents' ? 1 : 0,
	                        ]
,                        ],
                    ],
                    [
                        'label' => 'Заказ',
                        'items' => [
                            [
                                'label' => 'Совпадения',
                                'url' => [Url::to(['order/consilience'])],
                            ],
                            [
                                'label' => 'Клиенты',
                                'url' => ['/client'],
                                'active' => Yii::$app->controller->route == 'client/index' ? 1 : 0,
                            ],
                            [
                                'label' => 'Заказы',
                                'url' => ['/order'],
                                'active' => Yii::$app->controller->route == 'order/index' ? 1 : 0,
                            ],
	                        [
		                        'label' => 'Шаблоны',
		                        'url' => ['/notice/templates'],
		                        'active' => Yii::$app->controller->route == 'notice/templates' ? 1 : 0,
	                        ],
                        ],
                    ],
                    [
                        'label' => 'Статистика',
                        'items' => [
                            [
                                'label' => 'Статистика',
                                'url' => ['/admin/statistic'],
                                'active' => Yii::$app->controller->route == 'admin/statistic' ? 1 : 0,
                            ],
                            [
                                'label' => 'Корзина',
                                'url' => ['/admin/basket'],
                                'active' => Yii::$app->controller->route == 'admin/basket' ? 1 : 0,
                            ],
                        ],
                    ],
                    [
                        'label'   => Account::widget(),
                        'url'     => '#',
                        'encode'  => false,
                        'options' => [
                            'class' => 'pull-right account-icon',
                        ],
                    ],
				];
				break;
			case 2:
				$menu = [
					[
						'label' => 'Задачи',
						'url' => Url::to(['task/index']),
					],
                    [
                        'label' => 'Совпадения',
                        'url' => [Url::to(['order/consilience'])],
                    ],
					[
						'label' => 'База',
						'items' => [
							[
								'label' => 'Новые записи',
								'url' => [isset($_SESSION['newsSort']) ?'/agent/news?sort='.$_SESSION['newsSort'] : '/agent/news'],
								'active' => Yii::$app->controller->action->id == 'news' ? 1 : 0,
								'options' => [
									'class' => 'spec',
								],
							],
							[
								'label' => 'Общая база',
								'url' => [isset($_SESSION['baseSort']) ? '/agent/list?sort='.$_SESSION['baseSort'] : '/agent/list'],
								'active' => Yii::$app->controller->action->id == 'list' ? 1 : 0,
							],
                            [
                                'label' => 'Эксклюзивы',
                                'url' => Url::to(['custom/index']),
                                'active' => Yii::$app->controller->route == 'admin/agents' ? 1 : 0,
                                'visible' => \Yii::$app->user->identity->addingRecord == 1
                            ],
						],
					],
                    [
                        'label' => 'Заказы',
                        'url' => ['/order'],
                        'active' => Yii::$app->controller->route == 'order/index' ? 1 : 0,
                    ],
					[
						'label' => 'История',
						'url' => ['/agent/history'],
						'active' => Yii::$app->controller->route == 'agent/history' ? 1 : 0,
					],
					[
						'label'   => Account::widget(),
						'url'     => '#',
						'encode'  => false,
						'options' => [
							'class' => 'pull-right account-icon',
						],
					],
				];
				break;
		}

		return array_merge($menu, [
			[
				'label' => 'Выход',
				'url'   => ['/logout'],
			],
			[
				'label' => 'Обновить страницу',
				'url'   => '',
			],
		]);
	}

    public function getOrders() {
        return $this->hasMany(Order::className(), ['idAgent' => 'id']);
    }

    public static function getAgents() {
        return self::findAll(['role' => 2]);
    }

    public function isAvailable($idCategory) {
        if ($this->categories == null)
            return null;

        return in_array($idCategory, json_decode($this->categories));
    }

    public static function isAgent() {
        return Yii::$app->user->identity->role == 2;
    }

    public function getCategoryList() {
        $list = json_decode($this->categories);
        return Category::findAll(['id' => $list]);
    }
}
