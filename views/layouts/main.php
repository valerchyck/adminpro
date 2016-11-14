<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
	    <?
	    if (!Yii::$app->user->isGuest):
		    NavBar::begin([
			    'id' => 'main-menu',
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
		    ]);
		    echo Nav::widget([
			    'options' => ['class' => 'navbar-nav'],
			    'items' => \app\models\Users::getMenu(Yii::$app->user->identity->role),
		    ]);
		    NavBar::end();
	    endif
	    ?>
	    <div class="container">
		    <?= $content ?>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
