<?php
/**
 * @var $this \yii\web\View
 * @var $client \app\models\Client
 * @var $mode string
 */

$this->title = 'Клиент №'.$client->id;

use \kartik\detail\DetailView;

echo DetailView::widget([
    'id' => 'detail-view-client',
    'model' => $client,
    'hover' => true,
    'mode' => $mode == 'edit' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
    'panel' => [
        'heading' => 'Клиент №'.$client->id,
        'type' => DetailView::TYPE_PRIMARY,
        'headingOptions' => [
            'template' => $mode == 'edit' ? '{buttons}{title}' : '{title}',
        ],
    ],
    'attributes' => [
        'name',
        'mobilePhone',
        'homePhone',
        'skype',
        'email',
        'social',
        [
            'attribute' => 'comment',
            'type' => DetailView::INPUT_TEXTAREA,
        ]
    ],
    'buttons2' => '{save}{delete}',
    'deleteOptions' => [
        'url' => \yii\helpers\Url::to(['client/delete', 'id' => $client->id]),
    ],
]);
