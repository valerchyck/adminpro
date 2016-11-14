<?php
/**
 * @var yii\web\View $this
 * @var \app\models\Realty $model
 */

$this->title = 'Просмотр';
?>

<div class="row">
	<div class="col-md-6">
		<?= \yii\widgets\DetailView::widget([
			'model' => $model,
            'attributes' => [
                'id',
                'category.name',
                'agent',
                'area',
                'street',
                'metro',
                'roomCount',
                'furniture',
                'kitchen',
                'landArea',
                'floor',
                'floorCount',
                'price',
                'fphone',
                [
                    'attribute' => 'url',
                    'format' => 'html',
                    'value' => \yii\helpers\Html::a($model->url, $model->url),
                ],
                'text',
                'comment',
                'adminComment',
            ],
		]);
		?>
	</div>
	<div class="col-md-4">
		<?= \yii\helpers\Html::tag('div', '', ['id' => 'map']); ?>
	</div>
</div>

<script>
	mapKeywords = [];
	<? foreach ([$model->city, $model->area, $model->street, $model->metro] as $model): ?>
	<? if (!empty($model)): ?>
	mapKeywords.push(<?= \yii\helpers\Json::encode($model) ?>);
	<? endif ?>
	<? endforeach ?>

	google.maps.event.addDomListener(window, 'load', initialize);
</script>
