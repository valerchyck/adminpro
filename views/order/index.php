<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = 'Заказы';

use \yii\bootstrap\Modal;
use \app\models\Users;
use \yii\bootstrap\Tabs;
use \yii\data\ActiveDataProvider;
use \app\models\Order;

if (Users::isAgent()) {
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Все заказы',
                'content' => $this->render('grid', ['dataProvider' => $dataProvider]),
            ],
            [
                'label' => 'Мои заказы',
                'content' => $this->render('grid', [
                    'dataProvider' => new ActiveDataProvider([
                        'query' => Order::find()->where(['idAgent' => Yii::$app->user->identity->id]),
                    ]),
                ]),
                'options' => [
                    'id' => 'my-orders',
                ],
            ],
            [
                'label' => 'Добавить клиента',
                'content' => $this->render('//client/add', ['model' => new \app\models\Client()]),
            ],
        ],
    ]);
}
else {
    echo $this->render('grid', ['dataProvider' => $dataProvider]);
}

Modal::begin([
    'id' => 'add-order',
    'header' => '<h4>Новый заказ</h4>',
    'size' => Modal::SIZE_SMALL,
]);

echo $this->render('add', ['order' => new \app\models\Order()]);

Modal::end();
