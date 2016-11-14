<?php
/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'city',
        'area',
        'street',
        'metro',
        'roomCount',
        'furniture',
        'fullLandArea',
        'landArea',
        'floor',
        'floorCount',
        'price',
        'fphone',
    ],
    'rowOptions' => function($model, $key, $index, $grid) {
        return ['task' => $model->id];
    },
]);
?>

<script>
    $(document).ready(function() {
        $('.grid-view tbody tr').on('click', function(e) {
            var a = document.createElement('a');
            a.href = '/admin/get-map?id=' + $(this).attr('task');
            a.target = '_blank';
            $('body').append(a);
            a.click();
        });
    });
</script>
