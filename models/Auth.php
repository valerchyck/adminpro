<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class Auth extends Model {
	public $login;
	public $password;
	public $rememberMe = true;

	private $_user = false;

	public function rules() {
		return [
			[['login', 'password'], 'required'],
			['rememberMe', 'boolean'],
			['password', 'validatePassword'],
		];
	}

	public function attributeLabels() {
		return [
			'login' => 'Логин',
			'password' => 'Пароль',
		];
	}

	public function validatePassword() {
		if (!$this->hasErrors()) {
			$user = $this->getUser();

			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError('password', 'Incorrect username or password.');
			}
		}
	}

	public function login() {
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), 3600*24*30);
		} else {
			return false;
		}
	}

	public function getUser() {
		if ($this->_user === false) {
			$this->_user = Users::findOne(['login' => $this->login]);
		}

		return $this->_user;
	}
}
