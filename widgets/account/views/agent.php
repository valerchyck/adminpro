<?php
/**
 * @var $this \yii\web\View
 */

use \kartik\widgets\SwitchInput;
?>

<label>Готов взять задачу</label>
<?= SwitchInput::widget([
    'name'          => 'ready',
    'value'         => isset($_COOKIE['inWork']),
    'pluginOptions' => [
        'size'     => 'large',
        'onColor'  => 'success',
        'offColor' => 'danger',
        'onText'   => 'Да',
        'offText'  => 'Нет',
    ],
    'options'       => [
        'onchange' => 'setStatus(this)',
    ],
]);
