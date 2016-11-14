<?php
/**
 * @var \yii\web\View        $this
 * @var \app\models\Template $template
 */

use \kartik\form\ActiveForm;
use \yii\bootstrap\Html;
use \app\models\Template;
?>

<div class="row">
	<div class="col-md-12">
		<? $pjax = \yii\widgets\Pjax::begin([
			'formSelector'       => '#template-form',
			'enablePushState'    => false,
			'enableReplaceState' => false,
		]) ?>
			<? $form = ActiveForm::begin([
				'id'     => 'template-form',
				'action' => '/notice/save-template',
			]) ?>
			<?= $form->field($template, 'name')->textInput() ?>
			<?= $form->field($template, 'id')->hiddenInput()->label(false) ?>

			<label class="control-label">Макросы</label>
			<div class="form-group" style="float: left; border: 1px solid #959595; padding: 4px;">

				<? foreach (Template::macros() as $attr => $name): ?>
					<span class="macros">
						<button onclick="setMacros(this)" class="btn btn-default" type="button" value="<?= $attr ?>"><?= $name ?></button>
					</span>
				<? endforeach ?>
			</div>

			<?= $form->field($template, 'text')->textarea(['class' => 'template']) ?>
			<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
			<? $form->end() ?>
		<? $pjax->end() ?>
	</div>
</div>
