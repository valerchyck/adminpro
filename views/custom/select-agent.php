<?php
/**
 * @var $this     \yii\web\View
 * @var $idRealty integer
 */

use \app\models\Users;
use \app\models\ArrayHelper;
use \kartik\helpers\Html;
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Url;
?>

<? $form = ActiveForm::begin([
    'action' => Url::to(['custom/restore']),
    'options' => [
        'class' => 'form-horizontal',
    ],
]) ?>
    <div class="col-md-12">
        <div class="form-group">
            <?= Html::hiddenInput('Custom[idRealty]', $idRealty) ?>
            <?= Html::dropDownList('Custom[idAgent]', null, ArrayHelper::map(Users::getAgents(), 'id', 'name'), ['class' => 'form-control']); ?>
        </div>
    </div>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
<? $form->end();
