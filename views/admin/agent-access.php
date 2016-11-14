<?php
/**
 * @var $this \yii\web\View
 * @var $agent \app\models\Users
 * @var $categories[]
 * @var $agentCategories[]
 */

use \yii\helpers\Html;
?>

<div class="row">
    <div class="col-md-5">
        <?
            echo Html::checkboxList('categories', $agentCategories, $categories);
            echo Html::hiddenInput('agent', $agent->id);
        ?>
        <? if ($agent->role == 2): ?>
            <div style="margin-top: 10px; border-top: 1px solid #E5E5E5; width: 265px;">
                <label>
                    <?= Html::checkbox('dateFilter', $agent->dateFilter == 1, ['id' => 'date-filter']) ?>
                    Фильтр по дате
                </label>
            </div>
            <div style="width: 265px;">
                <label>
                    <?= Html::checkbox('clientInfo', $agent->clientInfo == 1, ['id' => 'client-info']) ?>
                    Информация о клиентах
                </label>
            </div>
            <div style="width: 265px;">
                <label>
                    <?= Html::checkbox('dopInfo', $agent->dopInfo == 1, ['id' => 'dop-info']) ?>
                    Дополнительная информация
                </label>
            </div>
	        <div style="width: 265px;">
		        <label>
			        <?= Html::checkbox('notice', $agent->notice == 1, ['id' => 'notice']) ?>
			        Рассылка
		        </label>
	        </div>
	        <div style="width: 265px;">
		        <label>
			        <?= Html::checkbox('addingRecord', $agent->addingRecord == 1, ['id' => 'add-record']) ?>
			        Добавление записей
		        </label>
	        </div>
        <? endif ?>
        <?= Html::button('Сохранить', [
                'id' => "submit",
                'class' => "btn btn-success",
            ]);
        ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#submit').on('click', function() {
            var selected = [];
            $.each($('[name="categories[]"]:checked'), function() {
                selected.push($(this).val());
            });

            $.post('/admin/save-agent-categories', {
                    data: JSON.stringify({
                        id: $('[name="agent"]').val(),
                        selected: selected,
                        dateFilter: $('[name="dateFilter"]').is(':checked') ? 1 : 0,
                        clientInfo: $('[name="clientInfo"]').is(':checked') ? 1 : 0,
                        dopInfo: $('[name="dopInfo"]').is(':checked') ? 1 : 0,
	                    notice: $('[name="notice"]').is(':checked') ? 1 : 0,
	                    addingRecord: $('[name="addingRecord"]').is(':checked') ? 1 : 0
                    })
                },
                function(response) {
                    $('.modal-dialog').parent().modal('hide');
            });
        });
    });
</script>
