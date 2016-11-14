<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $orders
 */

use \kartik\grid\GridView;

echo GridView::widget([
    'dataProvider' => $orders,
    'hover' => true,
    'resizableColumns' => false,
    'toolbar'=> [],
    'panel'=>[
        'type' => GridView::TYPE_PRIMARY,
        'heading' => 'Заказы',
        'headingOptions' => [
            'template' => '{title}'
        ],
    ],
    'columns' => [
        'id',
        'client.name',
        'agent.name',
        'category.name',
        'area',
        'street',
        'priceFrom',
        'priceTo',
    ],
]);
