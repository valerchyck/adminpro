<?php

namespace app\widgets\statistics;

use yii\web\AssetBundle;

class StatisticsAsset extends AssetBundle {
	public $sourcePath = '@app/widgets/statistics';

	public $css = [
		'css/style.css',
	];
	public $depends = [
		'yii\web\JqueryAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
