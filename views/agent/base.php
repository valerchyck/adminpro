<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $search \app\models\Realty
 * @var $categories \app\models\Category[]
 * @var $idCategory integer
 * @var $search \app\models\Realty
 * @var $dateFilter integer
 * @var $unCompleteTasks \app\models\Task[]
 * @var $completeTasks \app\models\Task[]
 */

use \yii\helpers\Url;
use \yii\bootstrap\Modal;

$this->title = 'Общая база';
?>

<? if (empty($categories)): ?>
	<fieldset>
		<legend class="bold">Раздел не открыт</legend>
	</fieldset>
<? else: ?>
    <fieldset>
        <legend class="bold blue"><?= $this->title ?></legend>
    </fieldset>

	<div class="btn-group" id="navigation">
		<input type="button" id="take" class="action btn btn-success" value="Взять задачу">

		<? if (Yii::$app->user->identity->dateFilter == 1): ?>
            <select class="selectpicker data-filter">
                <option<? if(empty($dateFilter)): ?> selected<?endif?> url="<?= Url::to(['agent/list', 'idCategory' => $idCategory]) ?>">Все записи</option>
                <option<? if($dateFilter == 1): ?> selected<?endif?> url="<?= Url::to(['agent/list', 'dateFilter' => 1, 'idCategory' => $idCategory]) ?>">За сегодня</option>
                <option<? if($dateFilter == 2): ?> selected<?endif?> url="<?= Url::to(['agent/list', 'dateFilter' => 2, 'idCategory' => $idCategory]) ?>">За вчера</option>
                <option<? if($dateFilter == 3): ?> selected<?endif?> url="<?= Url::to(['agent/list', 'dateFilter' => 3, 'idCategory' => $idCategory]) ?>">За ранний период</option>
            </select>
        <? endif ?>
	</div>

	<?= $this->render('/item/grid', [
		'dataProvider' 	  => $dataProvider,
		'search' 		  => $search,
		'unCompleteTasks' => $unCompleteTasks,
		'completeTasks'   => $completeTasks,
	]) ?>

    <!-- window for detail view -->
    <? Modal::begin([
        'header' => '<h4>Детали</h4>',
        'id'     => 'detail',
    ]);
    Modal::end(); ?>

<? endif ?>
