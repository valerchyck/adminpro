<?php
/**
 * @var $this yii\web\View
 * @var $item \app\models\Realty
 * @var $categories \app\models\Category[]
 */

use yii\widgets\ActiveForm;
use \kartik\widgets\Select2;
use \app\models\Realty;
use \yii\helpers\Html;

$this->title = 'Детали';
?>

<div class="row">
	<div class="col-md-6">
		<? $form = ActiveForm::begin([
			'id'      => 'item-form',
			'options' => ['class' => 'form-horizontal'],
			'fieldConfig' => [
				'template' => "<div class=\"col-lg-3\">{label}</div>\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
				'labelOptions' => ['class' => 'col-lg-1 control-label'],
			],
		]);

		echo $form->field($item, 'id')->hiddenInput()->label(false);
		echo $form->field($item, 'idCategory')->dropDownList($categories);
		echo $form->field($item, 'owner');
		echo $form->field($item, 'client');
		echo $form->field($item, 'agent');
		echo $form->field($item, 'city');
		echo $form->field($item, 'area')->widget(Select2::className(), [
			'data'    => Realty::getAreas(),
			'options' => [
				'placeholder' => 'Выберите район',
				'class'       => 'area',
				'onchange'    => "loadStreets(this, 'item-form')",
			],
		]);
		echo $form->field($item, 'street')->widget(Select2::className(), [
			'data'    => Realty::getStreets($item->area),
			'options' => [
				'placeholder' => 'Выберите улицу',
				'class'       => 'streets',
				'onchange'    => "loadMetro(this, 'item-form')",
			],
		]);
		echo $form->field($item, 'metro')->widget(Select2::className(), [
			'data'    => Realty::getMetro($item->street),
			'options' => [
				'placeholder' => 'Выберите метро',
				'class'       => 'metro',
			],
		]);
		echo $form->field($item, 'roomCount');
		echo $form->field($item, 'furniture');
		echo $form->field($item, 'state');
		echo $form->field($item, 'feature');
		echo $form->field($item, 'kitchen');
		echo $form->field($item, 'limit');
		echo $form->field($item, 'fullLandArea');
		echo $form->field($item, 'landArea');
		echo $form->field($item, 'floor');
		echo $form->field($item, 'floorCount');
		echo $form->field($item, 'price');
		echo $form->field($item, 'fphone');
		echo $form->field($item, 'sphone');
		echo $form->field($item, 'tphone');
		echo $form->field($item, 'frphone');
		echo $form->field($item, 'url');
		echo $form->field($item, 'text')->textarea(['style' => 'width: 1000px']);
		?>

		<? if (Yii::$app->user->identity->role == 1): ?>
			<?= Html::submitInput('Сохранить', ['class' => 'btn btn-success']) ?>
		<? endif ?>

		<? ActiveForm::end(); ?>
	</div>
	<div class="col-md-4">
		<?= Html::tag('div', '', ['id' => 'map']); ?>
	</div>
</div>

<script>
	mapKeywords = [];
	<? foreach ([$item->city, $item->area, $item->street, $item->metro] as $item): ?>
		<? if (!empty($item)): ?>
			mapKeywords.push(<?= json_encode($item) ?>);
		<? endif ?>
	<? endforeach ?>

	google.maps.event.addDomListener(window, 'load', initialize);
</script>
