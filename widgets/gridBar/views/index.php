<?php
/**
 * @var $this          \yii\web\View
 * @var $agents        \app\models\Users[]
 * @var $categories    Category[]
 * @var $category      integer
 * @var $action        string
 * @var $isHot         integer
 * @var $showStatistic bool
 * @var $buttons       bool
 */

use \yii\bootstrap\Modal;
use \app\models\Realty;
use \app\models\Category;
use \kartik\helpers\Html;
use \app\models\ArrayHelper;
use \app\widgets\statistics\Statistics;
?>

<div class="toolbar">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <?= Html::dropDownList('categories', $category, ArrayHelper::map($categories, 'id', 'name'), [
                                'class'     => 'form-control',
                                'title'     => 'Разделы',
                                'onchange'  => "setCategory(this, '$action')",
                            ]) ?>
                        </div>

                        <div class="col-md-2">
                            <?= Html::dropDownList('page-count', \Yii::$app->session->get('page-size'), [
                                20  => 20,
                                50  => 50,
                                100 => 100,
                                200 => 200,
                            ], [
                                'class'    => 'form-control',
                                'title'    => 'Кол-во записей на странице',
                                'onchange' => 'setPageSize(this)',
                            ]) ?>
                        </div>

                        <? if ($showStatistic): ?>
                            <div class="col-md-5">
                                <?= Statistics::widget([
                                    'idCategory' => $category,
                                    'isHot'      => $isHot,
                                    'fields'     => Yii::$app->user->identity->role == 1 ? null : ['comments'],
                                ]) ?>
                            </div>
                        <? endif ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3 right">
                <div class="right">
                    <button type="button" class="btn btn-default" onclick="print(this)" title="Распечатать">
                        <i class="glyphicon glyphicon-print"></i>
                    </button>

                    <? if (Yii::$app->user->identity->notice == 1): ?>
                        <button type="button" class="btn btn-default" onclick="sendForm(this)" title="Рассылка">
                            <i class="glyphicon glyphicon-envelope"></i>
                        </button>
                    <? endif ?>

                    <? if (Yii::$app->user->identity->role == 1): ?>
                        <? if (Yii::$app->controller->action->id == 'news'): ?>
                            <button type="button" class="btn btn-default" onclick="move(this)" title="Переместить в общую базу">
                                <i class="glyphicon glyphicon-share-alt"></i>
                            </button>
                        <? endif ?>

                        <button type="button" class="btn btn-success" onclick="$('#new-record-modal').modal('show')" title="Добавить новую запись">
                            <i class="glyphicon glyphicon-plus"></i>
                        </button>

                        <button type="button" class="btn btn-danger" onclick="removeItems(this)" title="Удалить записи">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                    <? endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?
Modal::begin([
    'header' => '<h4>Новая запись</h4>',
    'id'     => 'new-record-modal',
]);
echo $this->render('new-record', [
    'newRecord'  => new Realty(),
    'categories' => Category::getCategoriesMap(),
]);
Modal::end();

Modal::begin([
    'header' => '<h4>Рассылка</h4>',
    'id'     => 'send-template',
    'footer' => '
		<button type="button" class="btn btn-success" onclick="sendNotices(this)">Отправить на E-Mail</button>
	',
]);

Modal::end();
