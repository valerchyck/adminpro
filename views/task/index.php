<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use \yii\bootstrap\Modal;
use \yii\helpers\Html;
use \app\models\Task;
use \kartik\grid\GridView;

$this->title = "Задачи";
?>

<? if ($size == 'small'):
	echo GridView::widget([
		'id' => 'grid-tasks',
		'dataProvider' => $dataProvider,
		'columns' => [
			'city',
			'area',
			'street',
			'metro',
			'price',
			'fphone',
		],
	]);
else: ?>
	<form action="/task/finish" method="get">
		<div class="btn-group">
			<p>
				<input type="submit" id="finish" class="btn btn-success" value="Завершить">
			</p>
		</div>
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'id' => 'hot-list-grid',
			'options' => [
				'class' => 'grid-view info'
			],
			'columns' => [
				[
					'class' => \kartik\grid\CheckboxColumn::className(),
					'contentOptions' => function($model, $key, $index, $column) {
						$res = '';
						if (!empty($model->adminComment))
							$res = 'background: #3D85C6';

						return [
							'style' => $res,
						];
					}
				],
				'category.name',
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
					'class' => yii\grid\ActionColumn::className(),
					'template' => '{view}{delete}',
					'header' => 'Действие',
					'controller' => 'agent',
					'buttons' => [
						'delete' => function($url, $model, $key) {
							return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
								'title' => 'Отказаться',
								'data-confirm' => 'Вы уверены, что хотите отказаться от данной задачи?',
							]);
						}
					],
				],
			],
			'rowOptions' => function($model, $key, $index, $grid) {
				$task = Task::findOne(['idRealty' => $model->id]);
				return !empty($task) ? ['isFinish' => $task->status] : [''];

			},
		]);
		?>

		<!-- window for detail view -->
		<? Modal::begin([
			'header' => '<h4>Детали</h4>',
			'id' => 'detail',
		]);

		Modal::end(); ?>
	</form>
<? endif ?>
