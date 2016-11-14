<?php
/**
 * @var $this \yii\web\View
 * @var $realty \app\models\Realty
 */

use kartik\detail\DetailView;

echo DetailView::widget([
    'id' => 'detail-view-client',
    'model' => $realty,
    'hover' => true,
    'mode' => DetailView::MODE_VIEW,
    'attributes' => [
        'fphone',
        [
            'attribute' => 'url',
            'format' => 'html',
            'value' => '<a class="target" href="'.$realty->url.'">'.$realty->url.'</a>',
        ],
    ],
]);

$this->registerJs(<<<JS
    $('a.target').attr('target', '_blank');
JS
);
