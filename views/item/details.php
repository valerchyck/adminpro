<?php
/**
 * @var $this  \yii\web\View
 * @var $model \app\models\Realty
 */

use \yii\widgets\DetailView;
use \yii\helpers\Html;
use \yii\helpers\Json;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'text',
            'format' => 'text',
        ],
        [
            'attribute' => Yii::$app->user->identity->role == 1 ? 'comment' : 'adminComment',
            'format' => 'text',
        ],
        [
            'attribute' => 'url',
            'format' => 'html',
            'value' => '<a href="'.$model->url.'">'.$model->url.'</a>',
        ],
    ],
]);
?>

<div>
    <?= Html::label('Комментарий'); ?>
    <?= Html::hiddenInput('realty', $model->id); ?>
    <?= Html::textarea('comment', Yii::$app->user->identity->role == 1 ? $model->adminComment : $model->comment); ?>
    <?= Html::button('Оставить комментарий', ['class' => 'btn btn-success send-comment']) ?>
    <?= Html::button('Ожидание', [
        'class'   => 'btn btn-warning pull-right',
        'onclick' => "setEdited($model->id)",
    ]) ?>
</div>

<script>
    $(document).ready(function() {
        $('.send-comment').on('click', function() {
            $.post('/item/send-comment?id='+<?= Json::encode($model->id) ?>, {isAdmin: <?= Json::encode(Yii::$app->user->identity->role == 1) ?>, comment: $('[name="comment"]').val()}, function(response) {
                if (response == true)
                    alert('Комментарий отправлен');
            });
        });
    });
</script>
