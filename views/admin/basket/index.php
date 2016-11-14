<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $clients
 * @var \yii\data\ActiveDataProvider $realty
 * @var \app\models\Task[] $unCompleteTasks
 */

use \yii\bootstrap\Tabs;
use \yii\data\ActiveDataProvider;
use \app\models\UserRealty;

$this->title = 'Корзина';
?>

<fieldset>
    <legend class="bold"><?= $this->title ?></legend>
    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Записи',
                'content' => $this->render('realty', ['realty' => $realty, 'unCompleteTasks' => $unCompleteTasks]),
            ],
            [
                'label' => 'Клиенты',
                'content' => $this->render('clients', ['clients' => $clients]),
            ],
            [
                'label' => 'Эксклюзивы',
                'content' => $this->render('exclusive', [
                    'dataProvider' => new ActiveDataProvider([
                        'query' => UserRealty::find()->where(['forDelete' => 1]),
                        'pagination' => [
                            'pageSize' => 20,
                        ],
                    ])
                ]),
            ],
        ],
    ]);
    ?>

    <script>
        $(document).ready(function() {
            $('.restore').on('click', function() {
                if (!confirm('Вы уверены, что хотите восстановить элемент?'))
                    return false;
            });

            $('.drop-record').on('click', function() {
                if ($('#delete-realty [name="selection[]"]:checked').length < 1) {
                    alert('Выберите записи');
                    return false;
                }

                if (confirm('Вы уверены что хотите удалить данные записи?')) {
                    var selection = [];
                    $.each($('[name="selection[]"]:checked'), function () {
                        selection.push($(this).val());
                    });

                    $.post('/item/delete-from-basket', {selection: selection}, function () {
                        location.reload();
                    });
                }
            });
        });
    </script>
</fieldset>
