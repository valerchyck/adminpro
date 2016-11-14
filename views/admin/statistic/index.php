<?php
/**
 * @var $this \yii\web\View
 * @var $newsActive \app\models\Task[]
 * @var $generalActive \app\models\Task[]
 * @var $newsFinish \app\models\Task[]
 * @var $generalFinish \app\models\Task[]
 */

$this->title = 'Статистика';
?>

<table class="statistic-table">
    <tr>
        <td></td>
        <td colspan="2" style="font-weight: bold; border-right: 2px solid #3664A6; color: #008000">Активные</td>
        <td colspan="3" style="font-weight: bold; color: #ff2525">Завершено</td>
    </tr>
    <tr style="border-bottom: 2px solid #3664A6">
        <td></td>
        <td class="active">НЗ</td>
        <td class="active" style="border-right: 2px solid #3664A6">ОБ</td>
        <td class="finish">Д</td>
        <td class="finish">Н</td>
        <td class="finish">М</td>
    </tr>
    <? foreach ($categories as $item): ?>
        <tr>
            <td><?= $item->name ?></td>
            <td class="active">
                <a href="#" category="<?= $item->id ?>" isFinish="0" isHot="1">
                <?= count(\app\models\Task::find()->innerJoin('realty', 'realty.id = task.idRealty')->where(['realty.idCategory' => $item->id, 'isHot' => 1, 'task.status' => 0])->all())
                ?>
                </a>
            </td>
            <td class="active" style="border-right: 2px solid #3664A6">
                <a href="#" category="<?= $item->id ?>" isFinish="0" isHot="0">
                <?= count(\app\models\Task::find()->innerJoin('realty', 'realty.id = task.idRealty')->where(['realty.idCategory' => $item->id, 'isHot' => 0, 'task.status' => 0])->all())
                ?>
                </a>
            </td>
            <td class="finish">
                <a href="#" category="<?= $item->id ?>" isFinish="1" date="1">
                <?= count(\app\models\Task::find()->innerJoin('realty', 'realty.id = task.idRealty')->where('realty.idCategory = '.$item->id.' and task.status = 1 and DATEDIFF(NOW(), dateEnd) < 1 and TIME_TO_SEC(TIMEDIFF(NOW(), dateEnd))/3600 < 24')->all())
                ?>
                </a>
            </td>
            <td class="finish">
                <a href="#" category="<?= $item->id ?>" isFinish="1" date="2">
                <?= count(\app\models\Task::find()->innerJoin('realty', 'realty.id = task.idRealty')->where('realty.idCategory = '.$item->id.' and task.status = 1 and DATEDIFF(NOW(), dateEnd) <= 7')->all())
                ?>
                </a>
            </td>
            <td class="finish">
                <a href="#" category="<?= $item->id ?>" isFinish="1" date="3">
                <?= count(\app\models\Task::find()->innerJoin('realty', 'realty.id = task.idRealty')->where('realty.idCategory = '.$item->id.' and task.status = 1 and DATEDIFF(NOW(), dateEnd) <= 31')->all())
                ?>
                </a>
            </td>
        </tr>
    <? endforeach ?>
</table>

<?
\yii\bootstrap\Modal::begin([
    'header' => '<h4>Детали</h4>',
    'id' => 'details',
    'options' => [
        'class' => 'modal fade bs-example-modal-lg',
    ],
]);
?>
    <div id="details-grid-content"></div>
<? \yii\bootstrap\Modal::end(); ?>

<script>
    $(document).ready(function() {
        $('.statistic-table tbody tr:last').css('border-top', '2px solid #3664A6');
    });
</script>
