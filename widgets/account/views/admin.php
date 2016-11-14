<?php
/**
 * @var $this \yii\web\View
 */

use \yii\helpers\Url;
?>

<label>Готовы к работе:</label>
<a href="<?= Url::to(['agent/index']) ?>" style="color: green"><?= count(\app\models\Users::findAll(['inWork' => 1])) ?></a>
