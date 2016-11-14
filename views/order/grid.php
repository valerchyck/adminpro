<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use \kartik\grid\GridView;

echo GridView::widget([
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
                    'class'=>'btn btn-success add-order',
                    'onclick' => "$('#add-order').modal('show')",
                ]
            ),
        ],
    ],
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
        [
            'attribute' => 'agent.name',
            'visible' => !\app\models\Users::isAgent(),
        ],
        'category.name',
        'area',
        'street',
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
            'viewOptions' => ['label' => ''],
            'deleteOptions' => \app\models\Users::isAgent() ? ['label' => ''] : [],
            'width' => '8%',
        ],
    ],
	'rowOptions' => function($model, $key, $index, $grid) {
			$type = 'order';
			$options = [
				'last-record-type' => $type,
				'last-record-value' => $key,
			];

			if ((isset(Yii::$app->session->get('lastRecord')[$type]) && Yii::$app->session->get('lastRecord')[$type] == $key))
				$options['class'] = 'last-record';

			return $options;
		},
]);
