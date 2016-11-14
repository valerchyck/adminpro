<?php
/**
 * @var $this \yii\web\View
 * @var $client \app\models\Client
 * @var $order \app\models\Order
 */

use \kartik\form\ActiveForm;
use \kartik\field\FieldRange;
use \kartik\helpers\Html;
use \app\models\ArrayHelper;
use \app\models\Users;
use \app\models\Client;
use \app\models\Category;
use \kartik\widgets\Select2;
use \app\models\Realty;

$this->registerJs(<<<JS
    $('#new-order-form').find('input, select').on('change', function() {
        $('#empty-message').text('');
    });

    $('#new-order-form').on('submit', function() {
        var isEmpty = true;
        $(this).find('input, select').not(':hidden, button').each(function () {
            if ($(this).val().length > 0) {
                isEmpty = false;
                return false;
            }
        });

        if (isEmpty) {
            $('#empty-message').text('Нельзя отправить пустую форму');
            return false;
        }
    });
JS
);
?>

<? $form = ActiveForm::begin([
    'id' => 'new-order-form',
    'action' => '/order/add',
    'enableClientValidation' => true,
]); ?>

<? if (Yii::$app->user->identity->role == 1): ?>
    <?= $form->field($order, 'idAgent')->dropDownList(['Выберите агента'] + ArrayHelper::map(Users::getAgents(), 'id', 'name'),
        [
            'class' => 'selectpicker',
            'data-live-search' => 'true',
        ]) ?>
<? else: ?>
    <?= $form->field($order, 'idAgent')->hiddenInput(['value' => Yii::$app->user->identity->id])->label(false) ?>
<? endif ?>

<?= $form->field($order, 'idCategory')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'name'),
    [
        'class' => 'selectpicker',
        'data-live-search' => 'true',
    ]) ?>

<?= $form->field($order, 'idClient')->dropDownList(ArrayHelper::map(Client::find()->where(['forDelete' => 0])->all(), 'id', 'name'),
    [
        'class' => 'selectpicker',
        'data-live-search' => 'true',
    ]) ?>
<?= $form->field($order, 'area')->widget(Select2::className(), [
	'data'    => Realty::getAreas(),
	'options' => [
		'placeholder' => 'Выберите район',
		'class'       => 'area',
		'onchange'    => "loadStreets(this, 'new-order-form')",
	],
]) ?>
<?= $form->field($order, 'street')->widget(Select2::className(), [
	'data'    => Realty::getStreets($order->area),
	'options' => [
		'placeholder' => 'Выберите улицу',
		'class'       => 'streets',
		'onchange'    => "loadMetro(this, 'new-order-form')",
	],
]) ?>
<?= $form->field($order, 'metro')->widget(Select2::className(), [
	'data'    => Realty::getMetro($order->street),
	'options' => [
		'placeholder' => 'Выберите метро',
		'class'       => 'metro',
	],
]) ?>
<?= $form->field($order, 'roomCount') ?>
<?= $form->field($order, 'kitchen') ?>
<?= $form->field($order, 'floor') ?>
<?= $form->field($order, 'floorCount') ?>
<?= $form->field($order, 'landArea') ?>
<?= $form->field($order, 'fullLandArea') ?>
<?= FieldRange::widget([
    'form' => $form,
    'model' => $order,
    'label' => 'Цена',
    'attribute1' => 'priceFrom',
    'attribute2' => 'priceTo',
    'separator' => '<=',
]) ?>

<div id="empty-message" class="form-group" style="font-weight: bold; color: #D73D2A"></div>

<?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>

<? $form->end(); ?>
