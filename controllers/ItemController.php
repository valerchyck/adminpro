<?php

namespace app\controllers;

use app\models\Category;
use app\models\Realty;
use app\models\Task;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class ItemController extends \yii\web\Controller {
	public $enableCsrfValidation = false;

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin', 'agent'],
					],
				],
				'denyCallback' => function($rule, $action) {
                    $this->redirect('/login');
				}
			],
		];
	}

	public function actionNewRecord() {
		if (!empty($_POST['Realty'])) {
			$newRecord = new Realty($_POST['Realty']);
			$newRecord->date = date('Y-m-d H:i:s');

			$newRecord->save(false);
		}

		return $this->redirect('/admin/news', 301);
	}

	public function actionUpdate($id) {
        $realty = Realty::findOne(['id' => $id]);
        if ($realty == null)
            throw new NotFoundHttpException('item not found');

		if (($data = \Yii::$app->request->post('Realty')) != null) {
			$realty->setAttributes($data + ['ready' => 1]);
			$realty->save(false);
		}

		return $this->render('update', [
			'item'       => $realty,
			'categories' => Category::getCategoriesMap(),
		]);
	}

    public function actionRestore($id) {
        Realty::updateAll(['forDelete' => 0, 'deleteDate' => ''], ['id' => $id]);
		return $this->redirect('/admin/basket');
    }

	public function actionHide() {
        $deleted = 0;
        if (($data = \Yii::$app->request->post('selection')) != null) {
            $deleted = Realty::updateAll(['forDelete' => 1, 'deleteDate' => date('Y-m-d H:i:s')], ['id' => $data]);
        }

		return $deleted;
	}

    public function actionSetCategory($id, $action) {
        $key = $action == 'news' ? 'news-category' : 'base-category';
        \Yii::$app->session->set($key, $id);
    }

    public function actionPageSize() {
        if (($pageSize = \Yii::$app->request->post('size')) != null) {
            \Yii::$app->session->set('page-size', $pageSize);
        }
    }

	public function actionDelete($id) {
		Realty::deleteAll(['id' => $id]);
		return $this->redirect(\Yii::$app->request->referrer);
	}

	public function actionDetails($id) {
		return $this->renderPartial('details', [
			'model' => Realty::findOne(['id' => $id]),
		]);
	}

	public function actionSendComment($id) {
		$isAdmin = Json::decode($_POST['isAdmin']);
		$field = $isAdmin ? 'adminComment' : 'comment';
		return Realty::updateAll([$field => $_POST['comment']], ['id' => $id]);
	}

	public function actionDeleteFromBasket() {
		if (!empty($_POST['selection'])) {
			foreach ($_POST['selection'] as $item) {
				Realty::deleteAll(['id' => $item]);
			}
		}

		return $this->redirect(\Yii::$app->request->referrer);
	}

	public function actionResetComment($category, $isHot) {
		Realty::updateAll(['adminComment' => ''], ['isHot' => $isHot, 'idCategory' => $category]);
	}

    public function actionLastRecord($type = null, $value) {
        if ($type === null || $value === null)
            throw new \InvalidArgumentException('type and value are required');

        $lastRecord = \Yii::$app->session->get('lastRecord');

        $lastRecord[$type] = $value;
        \Yii::$app->session->set('lastRecord', $lastRecord);
    }

    public function actionSetEdited($id) {
        Realty::updateAll(['edited' => 1], ['id' => $id]);
    }
}
