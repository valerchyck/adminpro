<?php
/**
 * @var $this \yii\web\View
 * @var $newRecord \app\models\UserRealty
 * @var $categories \app\models\Category[]
 */

use \app\models\Realty;
use \kartik\widgets\Select2;
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \kartik\file\FileInput;
use \app\models\ArrayHelper;
?>

<div class="row">
    <div class="col-md-12">
        <? $form = ActiveForm::begin([
            'id'     => 'new-record-form',
            'action' => '/custom/create',
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);

        echo $form->field($newRecord, 'id')->hiddenInput()->label(false);
        echo $form->field($newRecord, 'idCategory')->dropDownList(ArrayHelper::map($categories, 'id', 'name'))
            ->label($newRecord->getAttributeLabel('idCategory') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'city');
        echo $form->field($newRecord, 'area')->widget(Select2::className(), [
            'data'    => Realty::getAreas(),
            'options' => [
                'placeholder' => 'Выберите район',
                'class'       => 'area',
                'onchange'    => "loadStreets(this, 'new-record-form')",
            ],
        ])->label($newRecord->getAttributeLabel('area') . ' <span class="hint-required">*</span>');;
        echo $form->field($newRecord, 'street')->widget(Select2::className(), [
            'data'    => Realty::getStreets($newRecord->area),
            'options' => [
                'placeholder' => 'Выберите улицу',
                'class'       => 'streets',
                'onchange'    => "loadMetro(this, 'new-record-form')",
            ],
        ])->label($newRecord->getAttributeLabel('street') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'metro')->widget(Select2::className(), [
            'data'    => Realty::getMetro($newRecord->street),
            'options' => [
                'placeholder' => 'Выберите метро',
                'class'       => 'metro',
            ],
        ])->label($newRecord->getAttributeLabel('metro') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'images[]')->widget(FileInput::className(), [
            'options' => [
                'accept'   => 'image/*',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'maxFileCount' => 5,
                'showUpload' => false,
            ],
        ])->label($newRecord->getAttributeLabel('images') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'roomCount')->label($newRecord->getAttributeLabel('roomCount') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'furniture');
        echo $form->field($newRecord, 'state');
        echo $form->field($newRecord, 'feature');
        echo $form->field($newRecord, 'kitchen');
        echo $form->field($newRecord, 'limit');
        echo $form->field($newRecord, 'fullLandArea')->label($newRecord->getAttributeLabel('fullLandArea') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'landArea')->label($newRecord->getAttributeLabel('landArea') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'floor')->label($newRecord->getAttributeLabel('floor') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'floorCount')->label($newRecord->getAttributeLabel('floorCount') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'price')->label($newRecord->getAttributeLabel('price') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'fphone')->label($newRecord->getAttributeLabel('fphone') . ' <span class="hint-required">*</span>');
        echo $form->field($newRecord, 'sphone');
        echo $form->field($newRecord, 'tphone');
        echo $form->field($newRecord, 'frphone');
        echo $form->field($newRecord, 'url');
        echo $form->field($newRecord, 'text')->textarea();

        echo Html::submitInput('Сохранить', ['class' => 'btn btn-success']);

        ActiveForm::end();
        ?>
    </div>
</div>
