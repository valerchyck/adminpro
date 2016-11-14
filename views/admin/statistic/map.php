<?php
/** @var \yii\web\View $this */
?>

<div class="col-md-12">
    <?= \yii\helpers\Html::tag('div', '', ['id' => 'map']); ?>
</div>

<script>
    mapKeywords = [];
    <? foreach ([$item->city, $item->area, $item->street, $item->metro] as $item): ?>
    <? if (!empty($item)): ?>
    mapKeywords.push(<?= \yii\helpers\Json::encode($item) ?>);
    <? endif ?>
    <? endforeach ?>

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
