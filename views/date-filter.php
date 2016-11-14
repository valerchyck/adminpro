<?php
/**
 * @var $this \yii\web\View
 * @var $route string
 * @var $idCategory integer
 * @var $idAgent integer
 * @var $selected integer
 */

use \yii\helpers\Url;
?>

<? if (Yii::$app->user->identity->role == 1 || (Yii::$app->user->identity->role == 2 && Yii::$app->user->identity->dateFilter == 1)): ?>
    <select class="selectpicker data-filter">
        <option<? if($selected == null): ?> selected<?endif?> url="<?= Url::to([$route, 'idCategory' => $idCategory, 'idAgent' => $idAgent]) ?>">Все записи</option>
        <option<? if($selected == 1): ?> selected<?endif?> url="<?= Url::to([$route, 'dateFilter' => 1, 'idCategory' => $idCategory, 'idAgent' => $idAgent]) ?>">За сегодня</option>
        <option<? if($selected == 2): ?> selected<?endif?> url="<?= Url::to([$route, 'dateFilter' => 2, 'idCategory' => $idCategory, 'idAgent' => $idAgent]) ?>">За вчера</option>
        <option<? if($selected == 3): ?> selected<?endif?> url="<?= Url::to([$route, 'dateFilter' => 3, 'idCategory' => $idCategory, 'idAgent' => $idAgent]) ?>">За ранний период</option>
    </select>
<? endif ?>
