<?php

namespace app\widgets\linkPager;

use yii\web\AssetBundle;

class LinkPagerAsset extends AssetBundle {
    public $sourcePath = '@app/widgets/linkPager';
    public $css = [
        'style.css',
    ];
    public $js = [
        'script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
