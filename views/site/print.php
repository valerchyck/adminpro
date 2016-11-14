<?php
/**
 * @var $this         \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use \yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id'           => 'for-print',
    'columns'      => [
        'city',
        'area',
        'street',
        'metro',
        'roomCount',
        'furniture',
        'fullLandArea',
        'landArea',
        'floor',
        'floorCount',
        'price',
        'fphone',
    ],
]);
