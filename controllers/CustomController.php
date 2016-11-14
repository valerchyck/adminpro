<?php
namespace app\controllers;

use app\models\Category;
use app\models\UserRealty;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class CustomController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'delete', 'restore', 'select-agent'],
                        'allow' => true,
                        'roles' => ['admin']
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['agent'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    $this->goHome();
                }
            ],
        ];
    }

    public function actionIndex() {
        $query = UserRealty::find()->where(['forDelete' => 0]);
        if (\Yii::$app->user->identity->role == 2)
            $query->andWhere(['idAgent' => \Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->session->get('page-size') == null ? 20 : \Yii::$app->session->get('page-size'),
            ],
        ]);

	    $sort = $dataProvider->getSort();
	    $sort->attributes['agent.id'] = [
		    'asc' => ['idAgent' => SORT_ASC],
		    'desc' => ['idAgent' => SORT_DESC],
		    'default' => SORT_DESC,
	    ];
	    $sort->attributes['category.name'] = [
		    'asc' => ['idCategory' => SORT_ASC],
		    'desc' => ['idCategory' => SORT_DESC],
		    'default' => SORT_DESC,
	    ];
	    $dataProvider->setSort($sort);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categories'   => \Yii::$app->user->identity->categoryList,
        ]);
    }

    public function actionCreate() {
        if (\Yii::$app->user->identity->addingRecord == 0)
            return $this->goHome();

        $realty = new UserRealty();
        if (($data = \Yii::$app->request->post('UserRealty')) != null) {
            $realty->setAttributes($data);

            $realty->images = UploadedFile::getInstances($realty, 'images');
            if ($realty->images != null) {
                $realty->upload();
            }

            $realty->save();
        }

        return $this->redirect(\Yii::$app->request->referrer, 301);
    }

    public function actionUpdate($id) {
        $realty = UserRealty::findOne(['id' => $id]);
        if ($realty == null)
            throw new NotFoundHttpException('item not found');

        if (($data = \Yii::$app->request->post('UserRealty')) != null) {
            $realty->setAttributes($data);
            $realty->save();

            return $this->redirect(Url::toRoute(['index']), 301);
        }

        return $this->render('update', [
            'realty' => $realty,
        ]);
    }

    public function actionDelete($id) {
        $realty = UserRealty::findOne(['id' => $id]);
        if ($realty->forDelete == 1)
            UserRealty::deleteAll(['id' => $id]);
        else
            UserRealty::updateAll(['forDelete' => 1]);

        return $this->redirect(\Yii::$app->request->referrer, 301);
    }

    public function actionRestore() {
        if (($data = \Yii::$app->request->post('Custom')) == null)
            throw new BadRequestHttpException('post data is invalid');

        UserRealty::updateAll(['forDelete' => 0, 'idAgent' => $data['idAgent']], ['id' => $data['idRealty']]);

        return $this->redirect(\Yii::$app->request->referrer, 301);
    }

    public function actionSelectAgent($id) {
        if (!\Yii::$app->request->isAjax)
            throw new BadRequestHttpException('request must be ajax');

        return $this->renderAjax('select-agent', [
            'idRealty' => $id,
        ]);
    }
}
