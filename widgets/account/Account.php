<?php
namespace app\widgets\account;

use yii\base\Widget;

class Account extends Widget {
	public function run() {
		AccountAsset::register($this->view);

        $view = \Yii::$app->user->identity->role == 1 ? 'admin' : 'agent';
		return $this->render('index', [
            'contentView' => $view,
        ]);
	}
}
