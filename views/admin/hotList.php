<?php
/**
 * @var $this \yii\web\View
 * @var $list \app\models\ListRealty[]
 * @var $agents \app\models\Users[]
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $search \app\models\Realty
 * @var $categories \app\models\Category[]
 * @var $idCategory integer
 * @var $grid string
 * @var $idAgent integer
 * @var $dateFilter integer
 * @var $unCompleteTasks \app\models\Task[]
 * @var $completeTasks \app\models\Task[]
 */

use \yii\helpers\Url;
use \yii\bootstrap\Modal;
use \app\widgets\statistics\Statistics;

$this->title = 'Общая база';
?>

<fieldset>
    <legend class="bold blue"><?= $this->title ?></legend>
</fieldset>

<div class="btn-group">
    <p>
        <input type="button" class="action btn btn-primary" onclick="addTask(this)" value="Назначить">
        <select class="selectpicker" name="agent">
            <option value="">Выберите агента</option>
            <? foreach ($agents as $item): ?>
                <option<? if(!$item->isAvailable($idCategory)): ?> data-icon="glyphicon-user"<? endif ?> <?= $item->id == $idAgent ? 'selected' : ''?> value="<?= $item->id ?>"><?= $item->name ?></option>
            <? endforeach ?>
        </select>

        <input type="button" id="finish" class="btn btn-success" value="Завершить">
        <select class="selectpicker data-filter">
            <option<? if(empty($dateFilter)): ?> selected<?endif?> url="<?= Url::to(['admin/hot-list', 'idCategory' => $idCategory, 'idAgent' => $idAgent]) ?>">Все записи</option>
            <option<? if($dateFilter == 1): ?> selected<?endif?> url="<?= Url::to(['admin/hot-list', 'dateFilter' => 1, 'idCategory' => $idCategory, 'idAgent' => $idAgent]) ?>">За сегодня</option>
            <option<? if($dateFilter == 2): ?> selected<?endif?> url="<?= Url::to(['admin/hot-list', 'dateFilter' => 2, 'idCategory' => $idCategory, 'idAgent' => $idAgent]) ?>">За вчера</option>
            <option<? if($dateFilter == 3): ?> selected<?endif?> url="<?= Url::to(['admin/hot-list', 'dateFilter' => 3, 'idCategory' => $idCategory, 'idAgent' => $idAgent]) ?>">За ранний период</option>
        </select>
    </p>
</div>

<div id="hot-grid">
    <?= $this->render('/item/grid', [
        'dataProvider' 	  => $dataProvider,
        'search' 		  => $search,
        'unCompleteTasks' => $unCompleteTasks,
        'completeTasks'   => $completeTasks,
    ]) ?>
</div>

<? Modal::begin([
    'header' => '<h4>Детали</h4>',
	'id' => 'detail',
]);

Modal::end(); ?>
