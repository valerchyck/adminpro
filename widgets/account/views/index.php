<?php
/**
 * @var $this        \yii\web\View
 * @var $contentView string
 */

use \kartik\popover\PopoverX;
use \kartik\helpers\Html;
?>

<?= PopoverX::widget([
	'placement'    => PopoverX::ALIGN_BOTTOM,
	'header'       => 'Настройки аккаунта',
	'size'         => PopoverX::SIZE_MEDIUM,
	'content'      => $this->render($contentView),
	'toggleButton' => [
		'label' => Html::icon('user'),
		'tag'   => 'span'
	],
]) ?>
