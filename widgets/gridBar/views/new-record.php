<?php
/**
 * @var $this \yii\web\View
 * @var $newRecord \app\models\Realty
 * @var $categories \app\models\Category[]
 */

use \app\models\Realty;
use \kartik\widgets\Select2;
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;

$form = ActiveForm::begin([
    'id'     => 'new-record-form',
    'action' => '/item/new-record',
]);

echo $form->field($newRecord, 'id')->hiddenInput()->label(false);
echo $form->field($newRecord, 'idCategory')->dropDownList($categories);
echo $form->field($newRecord, 'owner');
echo $form->field($newRecord, 'client');
echo $form->field($newRecord, 'agent');
echo $form->field($newRecord, 'city');
echo $form->field($newRecord, 'area')->widget(Select2::className(), [
    'data'    => Realty::getAreas(),
    'options' => [
        'placeholder' => 'Выберите район',
        'class'       => 'area',
        'onchange'    => "loadStreets(this, 'new-record-form')",
    ],
]);
echo $form->field($newRecord, 'street')->widget(Select2::className(), [
    'data'    => Realty::getStreets($newRecord->area),
    'options' => [
        'placeholder' => 'Выберите улицу',
        'class'       => 'streets',
        'onchange'    => "loadMetro(this, 'new-record-form')",
    ],
]);
echo $form->field($newRecord, 'metro')->widget(Select2::className(), [
    'data'    => Realty::getMetro($newRecord->street),
    'options' => [
        'placeholder' => 'Выберите метро',
        'class'       => 'metro',
    ],
]);
echo $form->field($newRecord, 'roomCount');
echo $form->field($newRecord, 'furniture');
echo $form->field($newRecord, 'state');
echo $form->field($newRecord, 'feature');
echo $form->field($newRecord, 'kitchen');
echo $form->field($newRecord, 'limit');
echo $form->field($newRecord, 'fullLandArea');
echo $form->field($newRecord, 'landArea');
echo $form->field($newRecord, 'floor');
echo $form->field($newRecord, 'floorCount');
echo $form->field($newRecord, 'price');
echo $form->field($newRecord, 'fphone');
echo $form->field($newRecord, 'sphone');
echo $form->field($newRecord, 'tphone');
echo $form->field($newRecord, 'frphone');
echo $form->field($newRecord, 'url');
echo $form->field($newRecord, 'text')->textarea();
echo $form->field($newRecord, 'comment')->textarea();
echo $form->field($newRecord, 'adminComment')->textarea();

echo Html::submitInput('Сохранить', ['class' => 'btn btn-success']);

ActiveForm::end();
