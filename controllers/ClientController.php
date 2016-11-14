<?php

namespace app\controllers;

use app\models\Client;
use app\models\Consilience;
use app\models\Order;
use app\models\Realty;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;

class ClientController extends \yii\web\Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'edit', 'delete', 'for-delete', 'restore'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['add', 'edit'],
                        'allow' => true,
                        'roles' => ['agent'],
                    ],
                    [
                        'actions' => ['data-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    $this->redirect('/login');
                }
            ],
        ];
    }

    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Client::find()->where(['forDelete' => 0]),
        ]);

        return $this->render('/client/index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdd() {
        $client = new Client();
        if (\Yii::$app->request->post('Client') !== null) {
            if ($client->load(\Yii::$app->request->post())) {
                $client->owner = \Yii::$app->user->identity->id;
                $client->save();
            }
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionDelete($id) {
        /**
         * @var $client Client
         */

        if (($client = Client::findOne(['id' => $id])) === null)
            throw new \InvalidArgumentException('client not found');

        Order::deleteAll(['idClient' => $id]);

        $client->delete($id);
        return $this->redirect(Url::to(['admin/basket']));
    }

    public function actionForDelete($id) {
        /**
         * @var $client Client
         */

        if (($client = Client::findOne(['id' => $id])) === null)
            throw new \InvalidArgumentException('client not found');

        foreach ($client->orders as $order) {
            Consilience::deleteAll(['idOrder' => $order->id]);
            $order->forDelete = 1;
            $order->save();
        }

        $client->forDelete = 1;
        $client->save();

        return $this->redirect(Url::to(['client/index']));
    }

    public function actionRestore($id) {
        /**
         * @var $client Client
         */

        if (($client = Client::findOne(['id' => $id])) === null)
            throw new \InvalidArgumentException('client not found');

        Order::updateAll(['forDelete' => 0], ['idClient' => $id]);

        $client->forDelete = 0;
        $client->save();

        return $this->redirect(Url::to(['admin/basket']));
    }

    public function actionDataList($name, $type) {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $list = [];
        switch ($type) {
            case 'street':
                $list = array_values(Realty::getStreets($name));
                break;

            case 'metro':
                $list = array_values(Realty::getMetro($name));
                break;
        }

        return $list;
    }

    public function actionEdit($id) {
        /**
         * @var $client Client
         */

        if (($client = Client::findOne(['id' => $id])) === null)
            throw new \InvalidArgumentException('client not found');

        if (($data = \Yii::$app->request->post('Client')) !== null) {
            $client->setAttributes($data);
            $client->save();
        }

        return $this->render('edit', ['client' => $client, 'mode' => 'edit']);
    }
}
