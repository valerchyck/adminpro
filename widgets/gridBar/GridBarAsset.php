<?php

namespace app\widgets\gridBar;

use yii\web\AssetBundle;

class GridBarAsset extends AssetBundle {
	public $sourcePath = '@app/widgets/gridBar';

	public $js = [
        'index.js',
	];

    public $css = [
        'index.css',
    ];
}
