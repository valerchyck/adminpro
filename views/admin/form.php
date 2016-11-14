<?php
/**
 * @var $this \yii\web\View
 * @var $user \app\models\Users
 */

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \kartik\widgets\Select2;
use \app\models\Realty;

$this->title = $user->id == null ? 'Новый пользователь' : 'Пользователь №' . $user->id;
?>

<? $form = ActiveForm::begin([
		'id'      => 'user-form',
		'options' => [
			'class' => 'form-horizontal',
		],
		'fieldConfig' => [
			'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-1 control-label'],
		],
	]);
	echo $form->field($user, 'login');

    if (Yii::$app->controller->action->id == 'add-user')
        echo $form->field($user, 'role')->dropDownList(['2' => 'Агент', '1' => 'Админ']);

	echo $form->field($user, 'password')->textInput(['value' => empty($user->passwordText) ? '' : $user->passwordText]);
	echo $form->field($user, 'repeatPassword')->textInput(['value' => '']);
	echo $form->field($user, 'name');
	echo $form->field($user, 'phone');
	echo $form->field($user, 'email');
	echo $form->field($user, 'skype');
	echo $form->field($user, 'icq');
	echo $form->field($user, 'web');
	echo $form->field($user, 'social');
	echo $form->field($user, 'birthDay');
	echo $form->field($user, 'birthMonth');
	echo $form->field($user, 'birthYear');
	echo $form->field($user, 'area')->widget(Select2::className(), [
		'data'    => Realty::getAreas(),
		'options' => [
			'placeholder' => 'Выберите район',
			'class'       => 'area',
			'onchange'    => "loadStreets(this, 'user-form')",
		],
	]);
	echo $form->field($user, 'street')->widget(Select2::className(), [
		'data'    => Realty::getStreets($user->area),
		'options' => [
			'placeholder' => 'Выберите улицу',
			'class'       => 'streets',
			'onchange'    => "loadMetro(this, 'user-form')",
		],
	]);
	echo $form->field($user, 'metro')->widget(Select2::className(), [
		'data'    => Realty::getMetro($user->street),
		'options' => [
			'placeholder' => 'Выберите метро',
			'class'       => 'metro',
		],
	]);
	echo $form->field($user, 'passport');
	echo $form->field($user, 'notDelete')->checkbox([], false);
	?>
	<div class="form-group">
		<div class="col-lg-1"></div>
		<div class="col-lg-8">
			<?= Html::submitInput('Сохранить', ['class' => 'btn btn-success']); ?>
		</div>
	</div>
<? ActiveForm::end(); ?>
