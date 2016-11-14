<?php
namespace app\controllers;

use app\models\ArrayHelper;
use app\models\Realty;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;

class TaskController extends \yii\web\Controller {
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'add', 'finish'],
						'allow'   => true,
						'roles'   => ['admin'],
					],
					[
						'actions' => ['index', 'finish'],
						'allow'   => true,
						'roles'   => ['agent'],
					],
				],
				'denyCallback' => function($rule, $action) {
					$this->goHome();
				}
			],
		];
	}

    public function actionIndex() {
	    $dataProvider = new ActiveDataProvider([
		    'query' => Realty::getTasks(\Yii::$app->user->id, 0),
		    'pagination' => [
			    'pageSize' => 20,
		    ],
	    ]);

	    $sort = $dataProvider->getSort();
	    $sort->attributes['category.name'] = [
		    'asc' => ['category.name' => SORT_ASC],
		    'desc' => ['category.name' => SORT_DESC,],
		    'default' => SORT_DESC,
	    ];
	    $sort->attributes['actualDate'] = [
		    'asc' => ['updatedAt' => SORT_ASC, 'createdAt' => SORT_ASC],
		    'desc' => ['updatedAt' => SORT_DESC, 'createdAt' => SORT_DESC],
		    'default' => SORT_DESC,
		    'label' => 'Дата',
	    ];
	    $dataProvider->setSort($sort);

	    if (\Yii::$app->request->isAjax)
		    return $this->renderAjax('index', [
			    'dataProvider' => $dataProvider,
			    'size' => 'small',
		    ]);

	    return $this->render('index', [
		    'dataProvider' => $dataProvider,
		    'size' => 'large',
	    ]);
    }

	public function actionAdd($idAgent = null, $taskIds = []) {
        if ($idAgent == null || $taskIds == null) {
            throw new BadRequestHttpException();
        }

		$tasks = ArrayHelper::map(Task::find()->all(), 'id', 'idRealty');
        foreach (explode(',', $taskIds) as $item) {
            if (in_array($item, $tasks))
                continue;

            $task = new Task([
                'idAgent' => $idAgent,
                'idRealty' => $item,
            ]);

            if (!$task->save(false)) {
                return json_encode($task->errors);
            }
        }

		return true;
	}

    public function actionFinish($tasks) {
        Task::updateAll(['status' => 1, 'dateEnd' => date('Y-m-d')], ['idRealty' => explode(',', $tasks)]);

        return true;
    }
}
