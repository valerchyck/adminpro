<?php

namespace app\components;

use yii\db\Query;
use yii\rbac\Role;
use yii\web\User;

class DbManager extends \yii\rbac\DbManager {
	private $assignments = [
		'1' => 'admin',
		'2' => 'agent',
	];

	public function checkAccess($userId, $permissionName, $params = []) {
		$user = (new Query())
			->from($this->assignmentTable)
			->where(['id' => (string) $userId])->one();

		if ($user == null || $this->assignments[$user['role']] != $permissionName)
			return false;

		return true;
	}
}
