<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $realty \app\models\Realty
 */

use \kartik\grid\GridView;
use \yii\bootstrap\Modal;

echo GridView::widget([
    'id' => 'order-realty',
    'dataProvider' => $dataProvider,
    'hover' => true,
    'resizableColumns' => false,
    'pjax' => true,
    'toolbar'=> [
        [
            'content' => \kartik\helpers\Html::button(
                '<i class="glyphicon glyphicon-plus"></i>',
                [
                    'type' => 'button',
                    'class'=>'btn btn-success',
                    'onclick' => "$('#add-order').modal('show')",
                ]
            ),
        ],
    ],
    'panel'=>[
        'type' => GridView::TYPE_PRIMARY,
        'heading' => 'Заказы',
    ],
    'columns' => [
        'area',
        'street',
        'metro',
        'roomCount',
        'fullLandArea',
        'landArea',
        'floor',
        'floorCount',
        'price',
    ],
    'rowOptions' => function($model, $key, $index, $grid) {
        $type = 'view-order';
        $options = [
            'isFinish' => '',
            'last-record-type' => $type,
            'last-record-value' => $key,
        ];

        if ((isset(Yii::$app->session->get('lastRecord')[$type]) && Yii::$app->session->get('lastRecord')[$type] == $key))
            $options['class'] = 'last-record';

        return $options;
    },
]);

Modal::begin([
    'id' => 'detail-realty',
]);

Modal::end();

$this->registerJs(<<<JS
    $('#order-realty tbody tr').on('click', function() {
        $.ajax({
            url: '/order/detail-realty',
            data: {id: $(this).attr('data-key')},
            success: function(response) {
                $('#detail-realty .modal-body').html(response);
                $('#detail-realty').modal('show');
            }
        });
    });
JS
);
