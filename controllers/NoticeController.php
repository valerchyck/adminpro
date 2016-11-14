<?php

namespace app\controllers;

use app\models\ArrayHelper;
use app\models\Realty;
use app\models\Template;
use app\models\Users;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NoticeController extends Controller {
    public function actionTemplates() {
        return $this->render('templates', [
	        'templates' => Template::find()->asArray()->all(),
        ]);
    }

	public function actionEditTemplate($id = null) {
		if (!\Yii::$app->request->isAjax)
			throw new BadRequestHttpException('request is not async');

		if ($id === null)
			$template = new Template();
		else {
			$template = Template::findOne(['id' => $id]);
			if ($template == null)
				throw new NotFoundHttpException("template $id not found");
		}

		return $this->renderAjax('edit', [
			'template' => $template,
		]);
	}

	public function actionSaveTemplate() {
		if (!\Yii::$app->request->isAjax)
			throw new BadRequestHttpException('request is not async');

		$data = \Yii::$app->request->post('Template');
		if ($data['id'] != null)
			$template = Template::findOne(['id' => $data['id']]);
		else
			$template = new Template();

		$template->setAttributes($data);
		$template->save();

		return $this->redirect('/notice/templates', 301);
	}
	
	public function actionRemoveTemplate($id) {
		if (!\Yii::$app->request->isAjax)
			throw new BadRequestHttpException('request is not async');

		$template = Template::findOne(['id' => $id]);
		if ($template == null)
			throw new NotFoundHttpException("template $id not found");

		$template->delete();
		return $this->redirect('/notice/templates', 301);
	}

	public function actionSendForm() {
		if (($ids = \Yii::$app->request->post('selection')) == null)
			throw new \InvalidArgumentException('data is empty');

		return $this->renderAjax('send', [
			'templates' => Template::find()->all(),
			'users'     => Users::find()->all(),
			'records'   => Realty::findAll(['id' => $ids]),
		]);
	}

	public function actionSend() {
	    $templateId = \Yii::$app->request->post('template');
		$template   = Template::findOne(['id' => $templateId]);
		if ($template == null)
			throw new NotFoundHttpException('template not found');

		$result = $template->generate(\Yii::$app->request->post('records'));

		$userIds = \Yii::$app->request->post('users');
		$emails   = Users::find()->select('email')->where(['id' => $userIds])->all();
		if ($emails == null)
			throw new NotFoundHttpException('email is empty for any user');

		$message = implode("\n------------------------------\n", $result);

		return mail(
			implode(',', ArrayHelper::getColumn($emails, 'email')),
			$template->name,
			$message
		);
	}
}
