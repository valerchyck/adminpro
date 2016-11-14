<?php

namespace app\controllers;

use app\models\Category;
use app\models\Realty;
use app\models\Task;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class AgentController extends \yii\web\Controller {
    public $enableCsrfValidation = false;

    public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['news', 'list', 'history', 'view', 'new-record',
                                      'set-work-status', 'custom', 'custom-update', 'custom-delete'],
						'allow'   => true,
						'roles'   => ['agent'],
					],
					[
						'actions' => ['hideTask', 'delete', 'index'],
						'allow'   => true,
						'roles'   => ['admin'],
					],
				],
				'denyCallback' => function($rule, $action) {
                    $this->goHome();
                }
			],
		];
	}

    public function beforeAction($action) {
        if (!isset($_COOKIE['inWork']))
            Users::updateAll(['inWork' => 0], ['id' => \Yii::$app->user->id]);

        return parent::beforeAction($action);
    }

	public function actionIndex() {
		return $this->render('index', [
			'dataProvider' => new ActiveDataProvider([
				'query' =>  Users::find(),
				'pagination' => [
					'pageSize' => 20,
				],
			])
		]);
	}

	public function actionHideTask($id) {
		Realty::updateAll(['hide' => 1], ['id' => $id]);
		$this->redirect(\Yii::$app->request->referrer);
	}

	public function actionDelete($id) {
		Task::deleteAll(['idRealty' => $id, 'idAgent' => \Yii::$app->user->id]);
		$this->redirect(\Yii::$app->request->referrer);
	}

	public function actionView($id) {
		return $this->render('view', [
			'model' => Realty::findOne(['id' => $id]),
		]);
	}

	public function actionHistory() {
		$dataProvider = new ActiveDataProvider([
			'query' => Realty::getTasks(\Yii::$app->user->id, 1),
			'pagination' => [
				'pageSize' => 20,
			],
		]);

		$sort = $dataProvider->getSort();
		$sort->attributes['task.dateEnd'] = [
			'asc' => ['task.dateEnd' => SORT_ASC],
			'desc' => ['task.dateEnd' => SORT_DESC,],
			'default' => SORT_DESC,
		];
        $sort->attributes['actualDate'] = [
            'asc' => ['updatedAt' => SORT_ASC, 'createdAt' => SORT_ASC],
            'desc' => ['updatedAt' => SORT_DESC, 'createdAt' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => 'Дата',
        ];
		$dataProvider->setSort($sort);

		return $this->render('history', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionNews($dateFilter = null) {
		$agent = Users::findOne(['id' => \Yii::$app->user->id]);
		$categories = Json::decode($agent->categories);

        $idCategory = \Yii::$app->session->get('news-category');
        if ($idCategory == null)
            $idCategory = Category::find()->asArray()->one()['id'];

		if (empty($categories))
			$categories = [];

		if (isset($_GET['sort']))
			$_SESSION['newsSort'] = $_GET['sort'];

        $query = Realty::getAgentNewsQuery($idCategory, $dateFilter);
        $search = new Realty();
        $search->load(\Yii::$app->request->get());
        $dataProvider = $search->search($query, \Yii::$app->request->get());

        $sort = $dataProvider->getSort();
        $sort->attributes['actualDate'] = [
            'asc' => ['updatedAt' => SORT_ASC, 'createdAt' => SORT_ASC],
            'desc' => ['updatedAt' => SORT_DESC, 'createdAt' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => 'Дата',
        ];
        $dataProvider->setSort($sort);

		$_SESSION['newsCategory'] = $idCategory;
		return $this->render('news', [
			'dataProvider' => $dataProvider,
            'search' => $search,
			'idCategory' => $idCategory,
			'dateFilter' => $dateFilter,
			'categories' => ArrayHelper::map(Category::find()->where(['id' => $categories])->asArray()->all(), 'id', 'name'),
			'unCompleteTasks' => Task::unCompleteTasks(),
			'completeTasks'   => Task::completeTasks(),
		]);
	}

	public function actionList($dateFilter = null) {
		$agent = Users::findOne(['id' => \Yii::$app->user->id]);
		$categories = Json::decode($agent->categories);

        $idCategory = \Yii::$app->session->get('base-category');
        if ($idCategory == null)
            $idCategory = Category::find()->asArray()->one()['id'];

		if (empty($categories))
			$categories = [];

		if (isset($_GET['sort']))
			$_SESSION['baseSort'] = $_GET['sort'];

        $query = Realty::getAgentBaseQuery($idCategory, $dateFilter);
        $search = new Realty();
        $search->load(\Yii::$app->request->get());
        $dataProvider = $search->search($query, \Yii::$app->request->get());

        $sort = $dataProvider->getSort();
        $sort->attributes['actualDate'] = [
            'asc' => ['updatedAt' => SORT_ASC, 'createdAt' => SORT_ASC],
            'desc' => ['updatedAt' => SORT_DESC, 'createdAt' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => 'Дата',
        ];
        $dataProvider->setSort($sort);

		$_SESSION['baseCategory'] = $idCategory;
		return $this->render('base', [
			'dataProvider' => $dataProvider,
            'search' => $search,
			'idCategory' => $idCategory,
			'dateFilter' => $dateFilter,
			'categories' => ArrayHelper::map(Category::find()->where(['id' => $categories])->asArray()->all(), 'id', 'name'),
			'unCompleteTasks' => Task::unCompleteTasks(),
			'completeTasks'   => Task::completeTasks(),
		]);
	}

	public function actionSetWorkStatus($value) {
        if ($value == 1)
            setcookie('inWork', 1, time() + 60 * 60, '/');
        else
            setcookie('inWork', null, null, '/');

        return Users::updateAll(['inWork' => $value], ['id' => \Yii::$app->user->id]);
    }
}
