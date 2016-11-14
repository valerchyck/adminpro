<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = 'Совпадения';

use \kartik\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'hover' => true,
    'resizableColumns' => false,
    'pjax' => true,
    'toolbar'=> [
        [
            'content' => \kartik\helpers\Html::button(
                '<i class="glyphicon glyphicon-search"></i>',
                [
                    'type' => 'button',
                    'class' => 'btn btn-success',
                    'onclick' => "window.location = '/admin/consilience';",
                ]
            ),
        ],
    ],
    'panel'=>[
        'type' => GridView::TYPE_PRIMARY,
        'heading' => 'Совпадения',
    ],
    'columns' => [
        'category.name',
        'area',
        'street',
        'metro',
        'roomCount',
        'kitchen',
        'floor',
        'floorCount',
        'landArea',
        'fullLandArea',
        'priceFrom',
        'priceTo',
        [
            'class' => 'kartik\grid\ActionColumn',
            'urlCreator' => function($action, $model, $key, $index) {
                if ($action == 'update')
                    $action = 'edit';

                return \yii\helpers\Url::to(["order/$action", 'id' => $model->id]);
            },
            'headerOptions' => ['class'=>'kartik-sheet-style'],
            'updateOptions' => ['label' => ''],
            'deleteOptions' => ['label' => ''],
            'width' => '8%',
        ],
    ],
    'rowOptions' => function($model, $key, $index, $grid) {
        $type = 'consilience';
        $options = [
            'last-record-type' => $type,
            'last-record-value' => $key,
        ];

        if ((isset(Yii::$app->session->get('lastRecord')[$type]) && Yii::$app->session->get('lastRecord')[$type] == $key))
            $options['class'] = 'last-record';

        return $options;
    },
]);
