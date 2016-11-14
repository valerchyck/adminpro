<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle {
//    public $sourcePath = '@app/views/layouts/control';
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	
    public $css = [
	    'css/admin/style.css',
	    'css/admin/codemirror.css',
	    'css/admin/show-hint.css',
    ];

    public $js = [
	    'js/admin/script.js',
	    'js/admin/codemirror.js',
	    'js/admin/sql.js',
	    'js/admin/css-hint.js',
	    'js/admin/sql-hint.js',
	    'js/admin/show-hint.js',
    ];

    public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
    ];
//	public $jsOptions = [
//		'position' => \yii\web\View::POS_HEAD,
//	];
}
