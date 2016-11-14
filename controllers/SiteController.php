<?php

namespace app\controllers;

use app\models\Auth;
use app\models\Helper;
use app\models\Realty;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

class SiteController extends Controller {
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['login', 'index'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['logout', 'index', 'print', 'login'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'actions' => ['error'],
						'allow' => true,
					],
				],
				'denyCallback' => function($rule, $action) {
                    $this->redirect('/');
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

	public function actionIndex() {
		if (Yii::$app->user->isGuest)
			return $this->redirect('/login', 301);

		if (Yii::$app->user->identity->role == 1)
			return $this->redirect(Url::to(['admin/news']), 301);
		else if (Yii::$app->user->identity->role == 2)
			return $this->redirect(Url::to(['task/index']), 301);
	}

	public function actionLogin() {
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new Auth();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			switch (Yii::$app->user->identity->role) {
				case '1':
					Yii::$app->setHomeUrl('/admin/hot-list');
					break;
				case '2':
					Yii::$app->setHomeUrl('/tasks');
					break;
			}
			return $this->goHome();
		}
		else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}

	public function actionPrint() {
        if (($data = \Yii::$app->request->post('selection')) != null) {
            return $this->render('print', [
                'dataProvider' => new ActiveDataProvider([
                    'query' => Realty::find()->where(['id' => $data]),
                    'sort' => false,
                ]),
            ]);
        }

        return true;
	}
}
