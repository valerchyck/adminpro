<?php
/**
 * @var \yii\web\View          $this
 * @var \app\models\Template[] $templates
 * @var \app\models\Users[]    $users
 * @var \app\models\Realty[]   $records
 */

use \yii\jui\Selectable;
use \kartik\widgets\Select2;
use \app\models\ArrayHelper;

$this->title = 'Отправка';
?>

<script>
	var records = <?= json_encode(ArrayHelper::getColumn($records, 'id')) ?>;
</script>

<div class="row">
	<div class="col-md-12">
		<div class="col-md-6">
			<fieldset>
				<legend>Юзеры</legend>
				<?= Selectable::widget([
					'id'    => 'send-users',
					'items' => ArrayHelper::map($users, 'id', function($item) {
						return [
							'content' => $item->name,
							'options' => ['data-id' => $item->id],
						];
					}),
				]);
				?>

				<div class="glyphicon glyphicon-info-sign">
					<span class="info">Для множественного выбора зажмите клавишу
						<span style="font-weight: bold;">Ctrl</span>
					</span>
				</div>
			</fieldset>
		</div>

		<div class="col-md-6">
			<fieldset>
				<legend>Шаблоны</legend>
				<?= Select2::widget([
					'data' => ArrayHelper::map($templates, 'id', 'name'),
					'name' => 'templates',
					'id'   => 'templates-select',
				]) ?>

				<div style="margin-top: 20px">
					<a href="/notice/templates" target="_blank">Перейти на страницу редактирования шаблонов</a>
				</div>
			</fieldset>
		</div>
	</div>
</div>
