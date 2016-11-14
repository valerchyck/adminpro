<?php
namespace app\widgets\account;

use yii\web\AssetBundle;

class AccountAsset extends AssetBundle {
	public $sourcePath = '@app/widgets/account';

	public $css = [
		'index.css',
	];

	public $js = [
		'index.js',
	];
}
