<?php
/**
 * @var \yii\web\View				 $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\UserRealty		 $search
 */

use \kartik\helpers\Html;
use \app\widgets\GridView;
use \yii\grid\ActionColumn;
use \app\models\UserRealty;
use \yii\bootstrap\Modal;

$this->title = 'Эксклюзивы';

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'toolbar'      => [
        [
            'content' => Yii::$app->user->identity->role == 2 ? Html::button(Html::icon('plus'), [
                'class'   => 'btn btn-success',
                'onclick' => "$('#new-custom-modal').modal('show')",
            ]) : '',
        ],
    ],
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
    ],
    'columns' => [
        [
            'attribute' => 'agent.id',
            'visible'   => Yii::$app->user->identity->role == 1,
        ],
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
        Yii::$app->user->identity->dopInfo == 1 ? 'fphone' : [],
        [
            'visible' => Yii::$app->user->identity->role == 2,
            'class' => ActionColumn::className(),
            'template' => '{update}{delete}',
            'header' => 'Действие',
            'urlCreator' => function($action, $model, $key, $index) {
                return "/custom/$action?id=$model->id";
            },
            'buttons' => [
                'update' => function($url, $model, $key) {
                    return Html::a(Html::icon('pencil'), $url, [
                        'title' => 'Редактировать',
                        'agent-id' => $model->id,
                    ]);
                },
                'delete' => function($url, $model, $key) {
                    return Html::a(Html::icon('trash'), $url, [
                        'title' => 'Удалить',
                        'class' => 'delete',
                        'agent-id' => $model->id,
                    ]);
                }
            ],
        ],
    ],
]);

Modal::begin([
    'header' => '<h4>Новая запись</h4>',
    'id'     => 'new-custom-modal',
]);
echo $this->render('create', [
    'newRecord'  => new UserRealty(),
    'categories' => \Yii::$app->user->identity->categoryList,
]);
Modal::end();
