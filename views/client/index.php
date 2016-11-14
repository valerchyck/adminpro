<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = 'Клиенты';

use \yii\bootstrap\Modal;
use \kartik\grid\GridView;
use \kartik\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'hover' => true,
    'resizableColumns' => false,
    'pjax' => true,
    'toolbar'=> [
        [
            'content' => \kartik\helpers\Html::button(
                '<i class="glyphicon glyphicon-plus"></i>',
                [
                    'type' => 'button',
                    'class'=>'btn btn-success',
                    'onclick' => "$('#add-client').modal('show')",
                ]
            ),
        ],
    ],
    'panel'=>[
        'type' => GridView::TYPE_PRIMARY,
        'heading' => 'Клиенты',
    ],
    'columns' => [
        'id',
        'user.name',
        'name',
        'mobilePhone',
        'homePhone',
        'skype',
        [
            'attribute' => 'social',
            'format' => 'html',
            'value' => function($model, $key, $index, $column) {
                if ($model->social != null)
                    return Html::a('<span class="glyphicon glyphicon-home"></span>', $model->social, ['class' => 'target']);
            },
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'urlCreator' => function($action, $model, $key, $index) {
                if ($action == 'update')
                    $action = 'edit';
                else if ($action == 'delete')
                    $action = 'for-delete';

                return \yii\helpers\Url::to(["client/$action", 'id' => $model->id]);
            },
            'headerOptions' => ['class'=>'kartik-sheet-style'],
            'viewOptions' => ['label' => ''],
            'deleteOptions' => Yii::$app->user->identity->role == 2 ? ['label' => ''] : [],
            'width' => '8%',
        ],
    ],
	'rowOptions' => function($model, $key, $index, $grid) {
			$type = 'client';
			$options = [
				'last-record-type' => $type,
				'last-record-value' => $key,
			];

			if ((isset(Yii::$app->session->get('lastRecord')[$type]) && Yii::$app->session->get('lastRecord')[$type] == $key))
				$options['class'] = 'last-record';

			return $options;
		},
]);

Modal::begin([
    'id' => 'add-client',
    'header' => '<h4>Новый клиент</h4>',
    'size' => Modal::SIZE_LARGE,
]);

echo $this->render('add', ['model' => new \app\models\Client()]);

Modal::end();

$this->registerJs(<<<JS
$('.target').attr('target', '_blank');
JS
);
