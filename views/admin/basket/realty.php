<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $realty
 * @var Task[] $unCompleteTasks
 */
use \yii\grid\GridView;
use \yii\grid\CheckboxColumn;
use \yii\helpers\ArrayHelper;
use \app\models\Task;
use yii\grid\ActionColumn;
use \yii\helpers\Html;
?>

<div class="btn-group">
    <p>
        <input type="button" class="btn btn-default print" value="Распечатать">
        <input type="button" class="btn btn-default drop-record" value="Удалить">
    </p>
</div>

<?= GridView::widget([
    'dataProvider' => $realty,
    'id' => 'delete-realty',
    'columns' => [
        [
            'class' => CheckboxColumn::className(),
            'checkboxOptions' => function($model, $key) {
                return ['value' => $key];
            },
            'contentOptions' => function($model, $key) use($unCompleteTasks) {
                $res = in_array($key, ArrayHelper::map($unCompleteTasks, 'id',
                    'idRealty')) ? 'background: #B3E59E' : '';
                return [
                    'style' => $res,
                ];
            }
        ],
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
        [
            'class' => ActionColumn::className(),
            'template' => '{restore}{delete}',
            'header' => 'Действие',
            'controller' => 'item',
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
    'rowOptions' => function($model, $key) use($unCompleteTasks) {
        $type = 'basket-realty';
        $options = [
            'last-record-type' => $type,
            'last-record-value' => $key,
        ];
        $options['style'] = in_array($key, ArrayHelper::map($unCompleteTasks, 'id', 'idRealty')) ? 'background: #B3E59E' : '';

        if ((isset(Yii::$app->session->get('lastRecord')[$type]) && Yii::$app->session->get('lastRecord')[$type] == $key))
            $options['class'] = 'last-record';

        return $options;
    },
]);
?>
