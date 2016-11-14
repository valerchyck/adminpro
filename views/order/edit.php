<?php
/**
 * @var $this     \yii\web\View
 * @var $order    \app\models\Order
 * @var $areas    array
 * @var $streets  array
 * @var $metro    array
 */

use \kartik\detail\DetailView;
use \yii\helpers\ArrayHelper;
use \yii\bootstrap\Collapse;
use \app\models\Client;
use \yii\helpers\Url;
use \app\models\Users;

$orderInfo = DetailView::widget([
    'id' => 'detail-view-order',
    'model' => $order,
    'hover' => true,
    'mode' => DetailView::MODE_EDIT,
    'panel' => [
        'heading' => 'Заказ №'.$order->id,
        'type' => DetailView::TYPE_PRIMARY,
        'headingOptions' => [
            'template' => Users::isAgent() ? '{title}' : '{buttons}{title}',
        ],
    ],
    'attributes' => [
        [
            'attribute' => 'idCategory',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => ArrayHelper::map(\app\models\Category::find()->all(), 'id', 'name'),
            ],
        ],
        [
            'attribute' => 'idAgent',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => ['Выберите агента'] + ArrayHelper::map(Users::getAgents(), 'id', 'name'),
            ],
            'visible' => Yii::$app->user->identity->role == 1,
        ],
        [
            'attribute' => 'idClient',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => ArrayHelper::map(Client::find()->where(['forDelete' => 0])->all(), 'id', 'name'),
            ],
            'visible' => Yii::$app->user->identity->role == 1,
        ],
        [
            'attribute' => 'area',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $areas,
                'pluginEvents' => [
                    'select2:select' => "function(){loadStreets(this, 'detail-view-order')}",
                ],
            ],
        ],
        [
            'attribute' => 'street',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $streets,
	            'pluginEvents' => [
		            'select2:select' => "function(){loadMetro(this, 'detail-view-order')}",
	            ],
            ],
	        'options' => [
		        'class' => 'streets',
	        ],
        ],
        [
            'attribute' => 'metro',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $metro,
            ],
	        'options' => [
		        'class' => 'metro',
	        ],
        ],
        'roomCount',
        'kitchen',
        'floor',
        'floorCount',
        'landArea',
        'fullLandArea',
        'priceFrom',
        'priceTo',
    ],
    'buttons2' => '{save}{delete}',
    'deleteOptions' => [
        'url' => Url::to(['order/delete', 'id' => $order->id]),
    ],
]);

$items = [
    [
        'label' => 'Заказ',
        'content' => $orderInfo,
        'contentOptions' => [
            'class' => 'in',
        ],
    ],
];

if (Yii::$app->user->identity->clientInfo == 1) {
    $items[] = [
        'label' => 'Данные клиента',
        'content' => $this->render('//client/edit', ['client' => Client::findOne(['id' => $order->idClient]), 'mode' => 'view']),
    ];
}

echo Collapse::widget([
    'items' => $items,
]);
