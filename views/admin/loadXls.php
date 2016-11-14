<?php
/**
 * @var $this \yii\web\View
 * @var $categories \app\models\Category[]
 */

$this->title = 'Загрузка XLS';
?>

<style>
    td, th {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <form id="load-xls" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <p><label>Раздел БД "АГЕНТПРО"</label><br/></p>
                    <p><label for="file">Загрузите XLS-файл</label></p>
                    <input type="submit">
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <select name="category">
                            <? foreach ($categories as $item): ?>
                                <option <? if ((isset(Yii::$app->session->get('lastRecord')['loadXls']) && Yii::$app->session->get('lastRecord')['loadXls'] == $item->id)): ?> selected<?endif?> filename="<?= $item->filename ?>" value="<?= $item->id ?>"><?= $item->name ?></option>
                            <? endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="file" id="file" name="xls" accept=".xls,.xlsx">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <?= \app\widgets\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => \app\models\Category::find(),
            ]),
            'layout' => '{items}',
            'columns' => [
                'name',
                'date',
            ],
        ]) ?>
    </div>
</div>

<script>
	$(document).ready(function() {
		$('#load-xls').on('submit', function() {
			if ($('[name="xls"]')[0].files.length == 0) {
				alert('Выберите файл');
				return false;
			}
			else if ($.inArray($('[name="xls"]')[0].files[0].name.split('.')[1], ['xls', 'xlsx']) < 0) {
				alert('Файл должен быть в формате XLS или XLSX');
				return false;
			}

            if (!validateFilename()) {
                alert('Выбраный файл не соответствует данному разделу');
                return false;
            }
		});

		$('[name="xls"]').on('change', function() {
			if ($.inArray($('[name="xls"]')[0].files[0].name.split('.')[1], ['xls', 'xlsx']) < 0) {
				alert('Файл должен быть в формате XLS или XLSX');
				$(this).val('');
				return false;
			}
		});

        $('#generate').on('click', function() {
            location = '/admin/generate-lists';
        });
	});

    function validateFilename() {
        if ($('[name="xls"]')[0].files[0] == undefined)
            return false;

        var selected = $('[name="xls"]')[0].files[0].name.split('.')[0];
        var filename = $('[name="category"] :selected').attr('filename');

        return filename == selected;
    }
</script>
