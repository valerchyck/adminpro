<?php
/* @var \yii\web\View $this */
/* @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = 'История';
?>

<fieldset>
    <legend class="bold">Завершенные задачи</legend>

    <div class="btn-group">
        <p>
            <input type="button" class="btn btn-default print" value="Распечатать">
        </p>
    </div>

<!--    --><?// \yii\widgets\Pjax::begin(); ?>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => \yii\grid\CheckboxColumn::className(),
                'contentOptions' => function($model, $key, $index, $column) {
                    return [
                        'style' => 'background: #e52d2d',
                    ];
                }
            ],
            [
                'label' => 'DATES',
                'attribute' => 'task.dateEnd',
                'value' => function ($data) {
                    return explode(' ', $data->task->dateEnd)[0];
                },
            ],
            'area',
            'street',
            'metro',
            'roomCount',
            'furniture',
            'floor',
            'floorCount',
            'price',
            'fphone',
            [
                'class' => yii\grid\ActionColumn::className(),
                'template' => '{hide-task}',
                'header' => 'Действие',
                'controller' => 'agent',
                'buttons' => [
                    'hide-task' => function ($url, $model, $key) {
                        return \yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'Удалить',
                            'class' => 'remove'
                        ]);
                    }
                ]
            ],
        ],
    ]);
    ?>

    <script>
        $(document).ready(function() {
            $('.remove').on('click', function() {
                if (!confirm('Вы уверены, что хотети удалить этот элемент?'))
                    return false;
            });
        });
    </script>

<!--    --><?// \yii\widgets\Pjax::end(); ?>
</fieldset>
