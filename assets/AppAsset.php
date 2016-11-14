<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
	    'css/bootstrap-select.min.css',
	    'css/alertify.default.css',
	    'css/alertify.core.css',
    ];
    public $js = [
	    'js/jquery.autosize.min.js',
	    'js/jquery.scrollTo-min.js',
	    'js/bootstrap-select.min.js',
	    'js/printThis.js',
	    'js/alertify.min.js',
	    'http://maps.google.com/maps/api/js?libraries=places&sensor=false',
	    'js/common.js',
	    'js/agent.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
	
	public $jsOptions = array(
		'position' => \yii\web\View::POS_HEAD,
	);
}
