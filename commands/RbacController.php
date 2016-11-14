<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\rbac\UserGroupRule;

class RbacController extends Controller
{
	public function actionInit()
	{
		if (file_exists(Yii::$app->basePath.'/rbac/items.php'))
			unlink(Yii::$app->basePath.'/rbac/items.php');
		if (file_exists(Yii::$app->basePath.'/rbac/rules.php'))
			unlink(Yii::$app->basePath.'/rbac/rules.php');
		if (file_exists(Yii::$app->basePath.'/rbac/assignments.php'))
			unlink(Yii::$app->basePath.'/rbac/assignments.php');

		$authManager = Yii::$app->authManager;

		// Create roles
		$guest  = $authManager->createRole('guest');
		$agent  = $authManager->createRole('agent');
		$admin  = $authManager->createRole('admin');

		// Create simple, based on action{$NAME} permissions
		$login  = $authManager->createPermission('login');
		$logout = $authManager->createPermission('logout');
		$error  = $authManager->createPermission('error');
		$index  = $authManager->createPermission('index');
		$view   = $authManager->createPermission('view');
		$update = $authManager->createPermission('update');
		$delete = $authManager->createPermission('delete');

		// Add permissions in Yii::$app->authManager
		$authManager->add($login);
		$authManager->add($logout);
		$authManager->add($error);
		$authManager->add($index);
		$authManager->add($view);
		$authManager->add($update);
		$authManager->add($delete);
		$authManager->add($admin);


		// Add rule, based on UserExt->group === $user->group
		$userGroupRule = new UserGroupRule();
		$authManager->add($userGroupRule);

		// Add rule "UserGroupRule" in roles
		$guest->ruleName  = $userGroupRule->name;
		$agent->ruleName  = $userGroupRule->name;
		$admin->ruleName  = $userGroupRule->name;

		// Add roles in Yii::$app->authManager
		$authManager->add($guest);
		$authManager->add($agent);
		$authManager->add($admin);

		$authManager->assign($admin, 1);
		$authManager->assign($agent, 2);

		// Add permission-per-role in Yii::$app->authManager
		// Guest
//		$authManager->addChild($guest, $login);
//		$authManager->addChild($guest, $error);
//
//		// Agent
//		$authManager->addChild($agent, $view);
//		$authManager->addChild($agent, $index);
//		$authManager->addChild($agent, $logout);
//		$authManager->addChild($admin, $error);
//
//		// Admin
//		$authManager->addChild($admin, $delete);
//		$authManager->addChild($admin, $update);
//		$authManager->addChild($admin, $agent);
	}
}
