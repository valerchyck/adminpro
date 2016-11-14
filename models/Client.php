<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property integer $owner
 * @property string $name
 * @property integer $mobilePhone
 * @property integer $homePhone
 * @property string $email
 * @property string $skype
 * @property string $social
 * @property string $comment
 * @property integer $forDelete
 * @property Order[] $orders
 */

class Client extends \yii\db\ActiveRecord {
    public static function tableName() {
        return 'client';
    }

    public function rules() {
        return [
            [['name'], 'required'],
            [['email'], 'email'],
            [['name', 'social'], 'string', 'max' => 255],
            [['comment'], 'string'],
            [['mobilePhone', 'homePhone', 'owner', 'forDelete'], 'integer'],
            [['skype'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'Ид',
            'owner' => 'Owner',
            'name' => 'ФИО',
            'mobilePhone' => 'Моб. тел',
            'homePhone' => 'Дом. тел',
            'email' => 'E-Mail',
            'skype' => 'Skype',
            'social' => 'Соц. сеть',
            'comment' => 'Комментарий',
            'forDelete' => 'Удален',
        ];
    }

    public function getUser() {
        return $this->hasOne(Users::className(), ['id' => 'owner']);
    }

    public function getOrders() {
        return $this->hasMany(Order::className(), ['idClient' => 'id']);
    }
}
