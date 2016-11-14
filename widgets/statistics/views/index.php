<?php
/**
 * @var $this \yii\web\View
 * @var $active \app\models\Task[]
 * @var $finish \app\models\Task[]
 * @var $comments \app\models\Realty[]
 * @var $idCategory integer
 * @var $isHot integer
 */

$this->registerJs(<<<JS
    // reset comments
    $('.admin.stat .comments').on('click', function() {
        if (confirm('Очистить комментарии?')) {
            $.get('/item/reset-comment', {category: $(this).attr('category'), isHot: $(this).attr('isHot')}, function() {
                location.reload();
            });
        }
    });
JS
)
?>

<span class="stat admin">
    <? if ($active !== null): ?>
        <label class="active"><?= count($active) ?></label>
    <? endif ?>

    <? if ($finish !== null): ?>
        <label class="finish"><?= count($finish) ?></label>
    <? endif ?>

    <? if ($comments !== null): ?>
        <label class="comments" category="<?= $idCategory ?>" isHot="<?= $isHot ?>"><?= count($comments) ?></label>
    <? endif ?>
</span>
