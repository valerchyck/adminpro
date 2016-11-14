<?php
/**
 * @var \yii\web\View         $this
 * @var \app\models\Template[]  $templates
 */

use \yii\helpers\Url;

$this->title = 'Шаблоны';
?>

<fieldset id="templates">
	<legend class="bold"><?= $this->title ?></legend>

	<div class="form-group">
		<button type="button" class="btn btn-success" onclick="edit()">
			Создать новый шаблон
		</button>
	</div>

	<div class="row">
		<? foreach (array_chunk($templates, 4) as $item): ?>
			<div class="row">
				<div class="col-md-12">
					<? foreach ($item as $template): ?>
						<div class="col-md-3">
							<div class="thumbnail">
								<div class="remove glyphicon glyphicon-remove" onclick="removeTemplate(<?=$template['id'] ?>)"></div>
								<div class="caption">
									<h3><?= $template['name'] ?></h3>
									<p class="content"><?= substr($template['text'], 0, 90).'...' ?></p>
									<p style="text-align: center">
										<button type="button" class="btn btn-warning" onclick="edit(<?=$template['id'] ?>)">Редактировать</button>
									</p>
								</div>
							</div>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		<? endforeach; ?>
	</div>
</fieldset>

<? \yii\bootstrap\Modal::begin([
	'header' => '<h4>Шаблон</h4>',
	'id'     => 'modal-template',
	'size'   => \yii\bootstrap\Modal::SIZE_LARGE,
]);

\yii\bootstrap\Modal::end(); ?>
