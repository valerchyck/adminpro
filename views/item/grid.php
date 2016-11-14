<?php
/**
 * @var \yii\web\View				 $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Realty			 $search
 * @var \app\models\Task[]			 $unCompleteTasks
 * @var \app\models\Task[]			 $completeTasks
 */

use \kartik\helpers\Html;
use \yii\helpers\ArrayHelper;
use \app\models\Task;
use \app\widgets\GridView;
use \yii\grid\CheckboxColumn;
use \yii\grid\ActionColumn;
use \app\widgets\gridBar\GridBar;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $search,
    'id'           => 'hot-list-grid',
    'pjax'         => true,
    'toolbar'      => [
        [
            'content' => GridBar::widget(),
        ],
    ],
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
    ],
    'options' => [
        'class' => 'grid-view',
    ],
    'columns' => [
        [
            'class'          => CheckboxColumn::className(),
            'contentOptions' => function($model, $key, $index, $column) use($unCompleteTasks, $completeTasks) {
                $res = '';
                if (in_array($key, ArrayHelper::map($unCompleteTasks, 'id', 'idRealty')))
                    $res = 'background: #6AA84F';
                else if (in_array($key, ArrayHelper::map($completeTasks, 'id', 'idRealty')))
                    $res = 'background: #CC4125';
                if (Yii::$app->user->identity->role == 2 && !empty($model->adminComment))
                    $res = 'background: #3D85C6';

                return [
                    'style' => $res,
                ];
            },
        ],
        [
            'attribute' => 'user.id',
            'label' => 'AG',
            'contentOptions' => function($model, $key, $index, $column) use($unCompleteTasks, $completeTasks){
                $res = '';
                if (in_array($key, ArrayHelper::map($unCompleteTasks, 'id', 'idRealty')))
                    $res = 'background: #6AA84F';
                else if (in_array($key, ArrayHelper::map($completeTasks, 'id', 'idRealty')))
                    $res = 'background: #CC4125';

                if (Yii::$app->user->identity->role == 1 && !empty($model->comment))
                    $res = 'background: #3D85C6';

                return [
                    'style' => $res,
                ];
            },
            'visible' => Yii::$app->user->identity->role == 1,
        ],
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
            'attribute'      => 'ready',
            'label'          => 'Г',
            'value'          => function ($model, $key, $index, $column) {
                return null;
            },
            'options'        => [
                'class' => 'col-small',
            ],
            'contentOptions' => function($model) {
                return $model->ready ? ['style' => 'background: #3D85C6'] : [];
            },
        ],
        [
            'attribute'      => 'edited',
            'label'          => 'С',
            'value'          => function ($model, $key, $index, $column) {
                return null;
            },
            'options'        => [
                'class' => 'col-small',
            ],
            'contentOptions' => function($model) {
                return $model->edited ? ['style' => 'background: #CC4125'] : [];
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{update}{delete}',
            'header' => 'Действие',
            'controller' => 'item',
            'urlCreator' => function($action, $model) {
                if ($action == 'delete')
                    $action = 'hide';

                return "/item/$action?id=$model->id";
            },
            'buttons' => [
                'update' => function($url, $model) {
                    if (Yii::$app->user->identity->role == 1)
                        return Html::a(Html::icon('pencil'), $url, [
                            'title' => 'Редактировать',
                            'agent-id' => $model->id,
                        ]);
                    else if (Yii::$app->user->identity->dopInfo == 1)
                        return Html::a(Html::icon('eye-open'), '/agent/view?id='.$model->id, [
                            'title' => 'Просмотр',
                            'agent-id' => $model->id,
                        ]);
                },
                'delete' => function($url, $model) {
                    if (Yii::$app->user->identity->role == 1)
                        return Html::a(Html::icon('trash'), $url, [
                            'title' => 'Удалить',
                            'class' => 'delete',
                            'agent-id' => $model->id,
                        ]);
                }
            ],
        ],
    ],
    'rowOptions' => function($model, $key) {
        $dopInfo = Yii::$app->user->identity->dopInfo == 1;

        $task = Task::findOne(['idRealty' => $model->id]);
        $type = $model->isHot ? 'news' : 'base';
        $options = [
            'isFinish' => '',
            'last-record-type' => $type,
            'last-record-value' => $key,
            'onclick' => 'showDetails(this, '.$dopInfo.')',
        ];

        if ((isset(Yii::$app->session->get('lastRecord')[$type]) && Yii::$app->session->get('lastRecord')[$type] == $key))
            $options['class'] = 'last-record';

        if (!empty($task))
            $options['isFinish'] = $task->status;

        return $options;
    },
]);
