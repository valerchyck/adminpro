<?php
/**
 * @var $this \yii\web\View
 * @var $realty \app\models\UserRealty
 */

use \yii\bootstrap\ActiveForm;
use \kartik\helpers\Html;
use \app\models\Category;
use \kartik\widgets\Select2;
use \app\models\Realty;
use \kartik\file\FileInput;

$this->title = 'Запись №' . $realty->id;
?>

<? $form = ActiveForm::begin([
    'id' => 'custom-form',
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
]);

echo $form->field($realty, 'id')->hiddenInput()->label(false);
echo $form->field($realty, 'idCategory')->dropDownList(Category::getCategoriesMap());
echo $form->field($realty, 'city');
echo $form->field($realty, 'area')->widget(Select2::className(), [
    'data'    => Realty::getAreas(),
    'options' => [
        'placeholder' => 'Выберите район',
        'class'       => 'area',
        'onchange'    => "loadStreets(this, 'new-record-form')",
    ],
]);
echo $form->field($realty, 'street')->widget(Select2::className(), [
    'data'    => Realty::getStreets($realty->area),
    'options' => [
        'placeholder' => 'Выберите улицу',
        'class'       => 'streets',
        'onchange'    => "loadMetro(this, 'new-record-form')",
    ],
]);
echo $form->field($realty, 'metro')->widget(Select2::className(), [
    'data'    => Realty::getMetro($realty->street),
    'options' => [
        'placeholder' => 'Выберите метро',
        'class'       => 'metro',
    ],
]);
echo $form->field($realty, 'images[]')->widget(FileInput::className(), [
    'options' => [
        'accept'   => 'image/*',
        'multiple' => true,
    ],
    'pluginOptions' => [
        'showPreview'          => true,
        'maxFileCount'         => 5,
        'showUpload'           => false,
        'overwriteInitial'     => false,
        'initialPreview'       => $realty->photos,
        'initialPreviewAsData' => true,
    ],
]);
echo $form->field($realty, 'roomCount');
echo $form->field($realty, 'furniture');
echo $form->field($realty, 'state');
echo $form->field($realty, 'feature');
echo $form->field($realty, 'kitchen');
echo $form->field($realty, 'limit');
echo $form->field($realty, 'fullLandArea');
echo $form->field($realty, 'landArea');
echo $form->field($realty, 'floor');
echo $form->field($realty, 'floorCount');
echo $form->field($realty, 'price');
echo $form->field($realty, 'fphone');
echo $form->field($realty, 'sphone');
echo $form->field($realty, 'tphone');
echo $form->field($realty, 'frphone');
echo $form->field($realty, 'url');
echo $form->field($realty, 'text')->textarea();

echo Html::submitInput('Сохранить', ['class' => 'btn btn-success']);

ActiveForm::end();
