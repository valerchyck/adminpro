<?php
/**
 * @var $this         \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use \kartik\grid\GridView;
use \kartik\helpers\Html;
use \yii\grid\ActionColumn;
use \yii\bootstrap\Modal;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'category.name',
        'area',
        'street',
        'metro',
        [
            'attribute' => 'roomCount',
            'options'   => [
                'class' => 'col-small',
            ],
        ],
        [
            'attribute' => 'fullLandArea',
            'options'   => [
                'class' => 'col-small',
            ],
        ],
        [
            'attribute' => 'landArea',
            'options'   => [
                'class' => 'col-small',
            ],
        ],
        [
            'attribute' => 'floor',
            'options'   => [
                'class' => 'col-small',
            ],
        ],
        [
            'attribute' => 'floorCount',
            'options'   => [
                'class' => 'col-small',
            ],
        ],
        'price',
        [
            'class' => ActionColumn::className(),
            'template' => '{restore}{delete}',
            'options' => [
                'class' => 'col-small',
            ],
            'header' => 'Действие',
            'controller' => 'custom',
            'buttons' => [
                'restore' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-open"></span>', '#', [
                        'title'   => 'Восстановить',
                        'onclick' => "restoreCustom($model->id)",
                    ]);
                }
            ],
        ],
    ],
]);

Modal::begin([
    'id' => 'restore-modal',
    'header' => '<h4>Выберите агента</h4>',
]);
Modal::end();
