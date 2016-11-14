<?php
/**
 * @var $this         \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use \yii\bootstrap\Modal;
use \kartik\grid\GridView;
use \kartik\helpers\Html;
use yii\grid\ActionColumn;
use \yii\helpers\Url;

$this->title = 'Пользователи';
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'toolbar'      => [
		[
			'content' => Html::button(
				'<i class="glyphicon glyphicon-plus"></i>',
				[
					'type'    => 'button',
					'class'   => 'btn btn-success',
					'onclick' => "window.location='/admin/add-user'",
				]
			),
		],
	],
	'panel' => [
		'type'    => GridView::TYPE_PRIMARY,
		'heading' => 'Пользователи',
	],
	'columns' => [
		'id',
		'area',
		'name',
		'phone',
		'skype',
		[
			'attribute' => 'social',
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				if ($model->social != null)
					return Html::a('<span class="glyphicon glyphicon-home"></span>', $model->social, ['class' => 'target']);
			},
		],
        [
            'attribute' => 'inWork',
            'label' => '',
            'value' => function ($model, $key, $index, $column) {
                return null;
            },
            'contentOptions' => function($model, $key, $index, $column) {
                return [
                    'style' => $model->inWork == 1 ? 'background: green' : 'background: red',
                ];
            },
        ],
		[
			'class'      => ActionColumn::className(),
			'template'   => '{tasks}{access}{update}{delete}',
			'header'     => 'Действие',
			'urlCreator' => function($action, $model, $key, $index) {
				if ($action == 'delete')
					return Url::to(['admin/agent-delete', 'id' => $model->id]);

				if ($action == 'update')
					return Url::to(['admin/update-agent', 'id' => $model->id]);
			},
			'buttons' => [
				'access' => function($url, $model, $key) {
					return Html::a('<span class="glyphicon glyphicon-cog"></span>', '#', [
						'title'    => 'Разделы',
						'agent-id' => $model->id,
						'onclick'  => 'agent.access(this)',
					]);
				},
				'delete' => function($url, $model, $key) {
					return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
						'title'    => 'Удалить',
						'agent-id' => $model->id,
						'class'    => 'delete',
					]);
				},
				'tasks' => function($url, $model, $key) {
					return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', $url, [
						'title'    => 'Задачи',
						'agent-id' => $model->id,
						'onclick'  => 'agent.tasks(this)',
					]);
				},
			],
		],
	],
	'rowOptions' => function($model, $key, $index, $grid) {
		$type = 'agent';
		$res = [
			'last-record-type' => $type,
			'last-record-value' => $key,
		];

		if ($model->notDelete == 1) {
			$res['style'] = 'background: darkgrey';
			$res['class'] = 'notDelete';
		}
		if ($model->role == 1)
			$res['style'] = 'background: #95B0E2';

		if ((isset(Yii::$app->session->get('lastRecord')[$type]) && Yii::$app->session->get('lastRecord')[$type] == $key))
			$res['class'] = ' last-record';

		return $res;
	},
]);
?>

<!-- agent's available categories -->
<?
Modal::begin([
	'header' => '<h4>Доступные разделы</h4>',
	'id' => 'agent-categories',
	'size' => Modal::SIZE_SMALL,
]);
Modal::end();


Modal::begin([
	'header' => '<h4>Задачи</h4>',
	'id' => 'agent-tasks',
	'size' => Modal::SIZE_LARGE,
]);
Modal::end();
?>
