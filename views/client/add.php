<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\models\Client
 */
?>

<div class="container">
    <div class="row">
        <? $pjax = \yii\widgets\Pjax::begin([
            'formSelector' => '#add-client-form',
            'enablePushState' => false,
            'enableReplaceState' => false,
        ]) ?>
        <? $form = \kartik\widgets\ActiveForm::begin([
            'id' => 'add-client-form',
            'action' => '/client/add',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]) ?>

<!--        --><?//= $form->field($model, 'idAgent')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role' => 2])->all(), 'id', 'firstName'), [
//            'class' => 'selectpicker',
//            'data-live-search' => 'true',
//        ]) ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'mobilePhone', ['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
        <?= $form->field($model, 'homePhone', ['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
        <?= $form->field($model, 'email', ['addon' => ['prepend' => ['content' => '@']]]) ?>
        <?= $form->field($model, 'skype') ?>
        <?= $form->field($model, 'social') ?>
        <?= $form->field($model, 'comment')->textarea() ?>

        <?= \kartik\helpers\Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>

        <? $form->end() ?>
        <? $pjax->end() ?>
    </div>
</div>
