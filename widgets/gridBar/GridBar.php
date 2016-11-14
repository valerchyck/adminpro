<?php
namespace app\widgets\gridBar;

use app\models\Category;
use app\models\Users;
use yii\base\Widget;

class GridBar extends Widget {
    public $showStatistic = true;
    public $buttons = true;

	public function run() {
		GridBarAsset::register($this->view);

        $action = \Yii::$app->controller->action->id;
        $key    = $action == 'news' ? 'news-category' : 'base-category';
        $isHot  = \Yii::$app->controller->action->id == 'news';
        $categories = Category::find();

        if (\Yii::$app->user->identity->role == 2)
            $categories->andWhere(['id' => json_decode(\Yii::$app->user->identity->categories)]);

		$category = \Yii::$app->session->get($key);
		if ($category == null)
			$category = Category::find()->one()->id;

		return $this->render('index', [
            'categories' => $categories->all(),
            'agents'     => Users::findAll(['role' => 2]),
            'category'   => $category,
            'action'     => $action,
            'isHot'      => $isHot,
            'showStatistic' => $this->showStatistic,
            'buttons' => $this->buttons,
        ]);
	}
}
