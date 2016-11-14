<?php

namespace app\controllers;

use app\models\Cache;
use app\models\Category;
use app\models\Client;
use app\models\Consilience;
use app\models\Helper;
use app\models\Realty;
use app\models\Settings;
use app\models\Task;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use app\models\ArrayHelper;
use yii\web\NotFoundHttpException;

class AdminController extends \yii\web\Controller {
	public $enableCsrfValidation = false;

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],
				],
				'denyCallback' => function($rule, $action) {
					$this->goHome();
				}
			],
		];
	}

	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionLoadXls() {
		if (!empty($_FILES['xls']['tmp_name']) && Helper::checkExtension($_FILES['xls']['name'], ['xls', 'xlsx'])) {
			$filename = Helper::xlsToCsv($_FILES['xls']['tmp_name']);

			/* parsing CSV and insert to DB. begin */
			$ar = array_map('str_getcsv', file($filename));
			if (Helper::isEmpty($ar))
				throw new NotFoundHttpException('file is empty');

            Realty::updateAll(['isHot' => 0], ['idCategory' => $_POST['category']]);

            $columns    = [];
            $keys       = [];
            $updateDate = date('Y-m-d H:i:s');
            $first      = array_shift($ar);

            for ($i = 0; $i < count($ar); $i++) {
                $attributes = [
					'idCategory' => $_POST['category'],
					'date'       => $updateDate,
	                'isHot'      => 1,
				];

				$phoneCounter = 1;
                $date = '';
                $dateP = '';
				for ($j = 0; $j < count($ar[$i]); $j++) {
					if ($first[$j] == null)
						continue;

                    $attr = mb_strtolower($first[$j], 'utf-8');

                    if ($attr == 'нас.п.' && mb_strtolower($ar[$i][$j], 'utf-8') != 'харьков')
                        break;

                    if (in_array($attr, ['дата'])) {
                        $date = strtotime($ar[$i][$j]);
                        continue;
                    }
                    if ($attr == 'дата п.') {
                        $dateP = strtotime($ar[$i][$j]);
                        continue;
                    }

					if (in_array($attr, ['datae', 'м', 'п']))
						continue;

					if ($first[$j] == 'Тел') {
						$first[$j] .= $phoneCounter;
						$phoneCounter++;
					}

                    $attributes[Realty::originName($first[$j])] = $ar[$i][$j];
				}

                $diff = $date - $dateP;
                $attributes['dateDiff'] = ($diff / 60 / 60 / 24) + 1;

                if ($attributes['dateDiff'] >= 3)
                    $attributes['dateDiff'] = 3;

                $columns[] = array_values($attributes);
                $keys      = array_keys($attributes);

                if ($i % 500 == 0) {
                    Helper::batchInsert($keys, $columns);
                }
			}

			Helper::batchInsert($keys, $columns);

            Category::updateAll(['date' => $updateDate], ['id' => $_POST['category']]);
			unlink($filename);
			/* parsing CSV and insert to DB. end */

            $lastRecord = \Yii::$app->session->get('lastRecord');
            $lastRecord['loadXls'] = $_POST['category'];
            \Yii::$app->session->set('lastRecord', $lastRecord);
		}

		return $this->render('loadXls', [
			'categories' => Category::find()->all(),
            'lastLoad' => Settings::findOne(['key' => 'lastLoad']),
		]);
	}

    public function actionConsilience() {
        Consilience::compare();

        return $this->redirect(\Yii::$app->request->referrer);
    }

	public function actionStatistic() {
        $task = Task::find()->innerJoin('realty', 'realty.id = task.idRealty');

		return $this->render('statistic/index', [
            'newsActive' => $task->where(['task.status' => 0, 'realty.isHot' => 1])->all(),
            'generalActive' => $task->where(['task.status' => 0, 'realty.isHot' => 0])->all(),

            'newsFinish' => $task->where(['task.status' => 1, 'realty.isHot' => 1])->all(),
            'generalFinish' => $task->where(['task.status' => 1, 'realty.isHot' => 0])->all(),
            'categories' => Category::find()->all(),
        ]);
	}

    public function actionGetStatistic($idCategory, $isFinish, $isHot = null, $date = null) {
        $where = [
            'task.status' => $isFinish,
            'realty.idCategory' => $idCategory,
        ];
        if ($isHot !== null)
            $where['realty.isHot'] = $isHot;

        $andWhere = [];

        if ($date !== null) {
            switch ($date) {
                case 1:
                    $andWhere = 'DATEDIFF(NOW(), task.dateEnd) < 1 and TIME_TO_SEC(TIMEDIFF(NOW(), task.dateEnd))/3600 < 24';
                    break;
                case 2:
                    $andWhere = 'DATEDIFF(NOW(), task.dateEnd) <= 7';
                    break;
                case 3:
                    $andWhere = 'DATEDIFF(NOW(), task.dateEnd) <= 31';
                    break;
            }
        }

        return $this->renderPartial('statistic/modal-content', [
            'agents' => Users::find()->innerJoin('task', 'task.idAgent = users.id')->innerJoin('realty', 'realty.id = task.idRealty')->where($where)->andWhere($andWhere)->all(),
            'idCategory' => $idCategory,
            'isFinish' => $isFinish,
            'date' => $date,
            'isHot' => $isHot,
        ]);
    }

    public function actionGetMap($id) {
        return $this->render('statistic/map', [
            'item' => Realty::findOne(['id' => $id]),
        ]);
    }

	public function actionAgents() {
		return $this->render('agents', [
			'dataProvider' => new ActiveDataProvider([
				'query' =>  Users::find(),
				'pagination' => [
					'pageSize' => 20,
				],
			])
		]);
	}

	public function actionAddUser() {
		$user = new Users();

		if (!empty($_POST['Users'])) {
			$user->setAttributes($_POST['Users']);
			$user->passwordText = $user->password;
			$user->password = md5($user->password);
			$user->repeatPassword = md5($user->repeatPassword);

            if ($user->role == 1) {
	            $user->dateFilter = 1;
	            $user->clientInfo = 1;
	            $user->dopInfo = 1;
            }

			$user->save();

			return $this->redirect(Url::to(['agent/index']), 301);
		}

		return $this->render('form', [
			'user' => $user,
		]);
	}

    public function actionMove() {
        if (($data = \Yii::$app->request->post('selection')) != null) {
            Realty::updateAll(['isHot' => 0], ['id' => $data]);
        }

        return true;
    }

    public function actionBasket() {
        return $this->render('basket/index', [
            'unCompleteTasks' => Task::unCompleteTasks(),
            'realty' => new ActiveDataProvider([
                'query' => Realty::find()->where(['forDelete' => 1]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]),
            'clients' => new ActiveDataProvider([
                'query' => Client::find()->where(['forDelete' => 1]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]),
        ]);
    }

    public function actionAgentDelete($id) {
        $user = Users::findOne(['id' => $id, 'notDelete' => 0]);
        if (!Helper::isEmpty($user)) {
            $conditions = ['idAgent' => $id, 'status' => 0];
            $tasks = array_keys(ArrayHelper::index(Task::findAll($conditions), 'idRealty'));

            Realty::updateAll(['idCategory' => 7], ['id' => $tasks]);
            Task::deleteAll($conditions);
            $user->delete();
        }

        return $this->redirect('/agents', 301);
    }

    public function actionAgentCategories($id) {
	    $agent = Users::findOne(['id' => $id]);

	    return $this->renderAjax('agent-access', [
		    'agent' => $agent,
		    'categories' => Category::getCategoriesMap(),
		    'agentCategories' => Json::decode($agent->categories),
	    ]);
    }

    public function actionSaveAgentCategories() {
        if (!empty($_POST['data'])) {
            $request = Json::decode($_POST['data']);

            /**
             * @var $agent Users
             */
            $agent = Users::findOne(['id' => $request['id']]);
            $agent->categories = Json::encode($request['selected']);
            $agent->dateFilter = $request['dateFilter'];
            $agent->clientInfo = $request['clientInfo'];
            $agent->dopInfo = $request['dopInfo'];
            $agent->notice = $request['notice'];
            $agent->addingRecord = $request['addingRecord'];

            $agent->save(false);
        }
    }

    public function actionUpdateAgent($id) {
        $agent = Users::findOne(['id' => $id]);

        if (!empty($_POST['Users'])) {
            if ($agent->role != $_POST['Users']['role']) {
                $roleName = '';

                switch ($_POST['Users']['role']) {
                    case 1:
                        $roleName = 'admin';
                        break;
                    case 2:
                        $roleName = 'agent';
                        break;
                }

                \Yii::$app->authManager->revokeAll($agent->id);
                \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole($roleName), $agent->id);
            }

            $agent->setAttributes($_POST['Users']);

            $agent->passwordText = $agent->password;
            $agent->password = md5($agent->password);
            $agent->repeatPassword = md5($agent->repeatPassword);

            $agent->save();
        }

        return $this->render('form', [
            'user' => $agent,
        ]);
    }

    public function actionNews($idAgent = null, $dateFilter = null) {
        if (isset($_GET['sort']))
            $_SESSION['newsSort'] = $_GET['sort'];

        if (\Yii::$app->request->post('news-page-count') != null) {
            \Yii::$app->session->set('news-page-count', \Yii::$app->request->post('news-page-count'));
        }

        $idCategory = \Yii::$app->session->get('news-category');
        if ($idCategory == null)
            $idCategory = Category::find()->asArray()->one()['id'];

        $query = Realty::getAdminNewsQuery($idCategory, $dateFilter);
        $search = new Realty();
        $search->load(\Yii::$app->request->get());
        $dataProvider = $search->search($query, \Yii::$app->request->get());

        $sort = $dataProvider->getSort();
        $sort->attributes['user.id'] = [
            'asc' => ['users.id' => SORT_ASC],
            'desc' => ['users.id' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => 'AG',
        ];
        $dataProvider->setSort($sort);

        return $this->render('news', [
            'dataProvider' => $dataProvider,
            'search' => $search,
            'categories' => Category::getCategoriesMap(),
            'idCategory' => $idCategory,
            'dateFilter' => $dateFilter,
            'idAgent' => $idAgent,
            'agents' => Users::findAll(['role' => 2]),
	        'unCompleteTasks' => Task::unCompleteTasks(),
	        'completeTasks' => Task::completeTasks(),
        ]);
    }

    public function actionHotList($idAgent = null, $dateFilter = null) {
        if (isset($_GET['sort']))
            $_SESSION['baseSort'] = $_GET['sort'];

        $idCategory = \Yii::$app->session->get('base-category');
        if ($idCategory == null)
            $idCategory = Category::find()->asArray()->one()['id'];

        $search = new Realty();
        $query = Realty::getAdminBaseQuery($idCategory, $dateFilter);
        $search->load(\Yii::$app->request->get());
        $dataProvider = $search->search($query, \Yii::$app->request->get());

        $sort = $dataProvider->getSort();
        $sort->attributes['user.id'] = [
            'asc' => ['users.id' => SORT_ASC],
            'desc' => ['users.id' => SORT_DESC,],
            'default' => SORT_DESC,
            'label' => 'AG',
        ];
        $sort->attributes['actualDate'] = [
            'asc' => ['updatedAt' => SORT_ASC, 'createdAt' => SORT_ASC],
            'desc' => ['updatedAt' => SORT_DESC, 'createdAt' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => 'Дата',
        ];
        $dataProvider->setSort($sort);

        return $this->render('hotList', [
            'search' => $search,
            'agents' => Users::findAll(['role' => 2]),
            'dataProvider' => $dataProvider,
            'idCategory' => $idCategory,
            'idAgent' => $idAgent,
            'dateFilter' => $dateFilter,
            'categories' => Category::getCategoriesMap(),
			'unCompleteTasks' => Task::unCompleteTasks(),
			'completeTasks' => Task::completeTasks(),
        ]);
    }

    public function actionClientOrders($id) {
        /**
         * @var Client $client
         */

        if (($client = Client::findOne(['id' => $id])) === null)
            throw new \InvalidArgumentException('client not found');

        return $this->renderPartial('basket/orders', [
            'orders' => new ActiveDataProvider([
                'query' => $client->getOrders(),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]),
        ]);
    }
}
