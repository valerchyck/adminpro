<?php

namespace app\controllers;

use app\models\Consilience;
use app\models\Order;
use app\models\Realty;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;

class OrderController extends \yii\web\Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'edit', 'view', 'delete', 'consilience', 'detail-realty'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['index', 'add', 'edit', 'view', 'consilience', 'detail-realty'],
                        'allow' => true,
                        'roles' => ['agent'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    $this->redirect('/login');
                }
            ],
        ];
    }

    public function actionIndex() {
        $query = Order::find()->where(['forDelete' => 0]);
        if (\Yii::$app->user->identity->role == 2) {
            $query->andWhere(['idAgent' => 0]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('/order/index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdd() {
        if (($data = \Yii::$app->request->post('Order')) !== null) {
            $order = new Order(\Yii::$app->request->post('Order'));
            $order->save();
        }

        return $this->redirect(Url::to(['order/index']));
    }

    public function actionEdit($id) {
        $order = Order::findOne(['id' => $id]);

        if (\Yii::$app->request->post('Order') !== null) {
            /**
             * @var $order Order
             */

            if (($data = \Yii::$app->request->post('Order')) != null) {
                $order->setAttributes($data);
                $order->save();
            }
        }

	    $streets = Realty::getStreets($order->area);
	    $metro   = Realty::getMetro($order->street);

        return $this->render('/order/edit', [
            'order' => $order,
            'areas' => Realty::getAreas(),
            'streets' => $streets,
            'metro' => $metro,
        ]);
    }

    public function actionDelete($id) {
        /**
         * @var $order Order
         */

        if (($order = Order::findOne(['id' => $id])) === null)
            throw new \InvalidArgumentException('order not found');
        $order->delete();

        return $this->redirect(Url::to(['order/index']));
    }

    public function actionConsilience() {
        $dataProvider = new ActiveDataProvider([
            'query' => Consilience::getFound(),
        ]);

        return $this->render('consilience', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        /**
         * @var $order Order
         */
        $order = Order::findOne(['id' => $id]);
        if ($order === null)
            throw new \InvalidArgumentException('order not found');

        $query = $order->getRealty();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'realty' => $order->realty,
        ]);
    }

    public function actionDetailRealty($id) {
        $realty = Realty::findOne(['id' => $id]);
        if ($realty === null)
            throw new \InvalidArgumentException('realty not found');

        return $this->renderAjax('detail', [
            'realty' => $realty,
        ]);
    }
}
