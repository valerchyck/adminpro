<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $message;
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
