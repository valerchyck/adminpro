<?php
/** @var $this \yii\web\View */
/** @var $agents \app\models\Users[] */
/** @var $idCategory integer */
/** @var $isFinish integer */
/** @var $isHot integer */
/** @var $date string */

$items = [];
foreach ($agents as $item) {
    $items[] = [
        'label' => 'Агент №'.$item->id.', '.$item->name.' - '.$item->phone,
        'content' => $this->render('grid-content', [
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $item->getTasks($idCategory, $isFinish, $isHot, $date),
                'pagination' => false,
            ]),
        ]),
    ];
}

echo \yii\bootstrap\Collapse::widget([
    'items' => $items
]);
