<?php
/**
 * @var $this    \yii\web\View
 * @var $clients \yii\data\ActiveDataProvider
 */

use \yii\bootstrap\Modal;
use \yii\grid\GridView;
use \kartik\helpers\Html;
use \yii\grid\ActionColumn;
?>

<?= GridView::widget([
    'dataProvider' => $clients,
    'columns' => [
        'id',
        'user.name',
        'name',
        'mobilePhone',
        'homePhone',
        'skype',
        [
            'attribute' => 'social',
            'format' => 'html',
            'value' => function($model) {
                if ($model->social != null)
                    return Html::a('<span class="glyphicon glyphicon-home"></span>', $model->social, ['class' => 'target']);
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{restore}{delete}',
            'header' => 'Действие',
            'controller' => 'client',
            'buttons' => [
                'restore' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-open"></span>', $url, [
                        'title' => 'Восстановить',
                        'class' => 'restore',
                    ]);
                }
            ],
        ],
    ],
    'rowOptions' => function($model, $key) {
        $type = 'basket-client';
        $options = [
            'last-record-type' => $type,
            'last-record-value' => $key,
            'onclick' => "clientOrders($key)",
        ];

        if ((isset(Yii::$app->session->get('lastRecord')[$type]) && Yii::$app->session->get('lastRecord')[$type] == $key))
            $options['class'] = 'last-record';

        return $options;
    },
]);

Modal::begin([
    'id' => "orders",
]);
Modal::end();
