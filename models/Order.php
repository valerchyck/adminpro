<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $idCategory
 * @property integer $idAgent
 * @property integer $idClient
 * @property string $area
 * @property string $street
 * @property string $metro
 * @property integer $roomCount
 * @property integer $kitchen
 * @property integer $floor
 * @property integer $floorCount
 * @property double $landArea
 * @property double $fullLandArea
 * @property integer $priceFrom
 * @property integer $priceTo
 * @property integer $forDelete
 * @property ActiveQuery $realty
 */

class Order extends \yii\db\ActiveRecord {
    public static function tableName() {
        return 'order';
    }

    public function beforeSave($insert) {
        if ($this->priceFrom !== null)
            $this->priceFrom .= '000';

        if ($this->priceTo !== null)
            $this->priceTo .= '000';

        return parent::beforeSave($insert);
    }

    public function rules() {
        return [
            [['idClient', 'idCategory'], 'required'],
            [['idAgent', 'idClient', 'roomCount', 'kitchen', 'floor', 'floorCount', 'priceFrom', 'priceTo', 'forDelete'], 'integer'],
            [['landArea', 'fullLandArea'], 'number'],
            [['area', 'street', 'metro'], 'string', 'max' => 255],
            ['priceFrom', 'compare', 'compareAttribute' => 'priceTo', 'operator' => '<='],
            ['priceTo', 'compare', 'compareAttribute' => 'priceFrom', 'operator' => '>='],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'Ид',
            'idCategory' => 'Раздел',
            'idAgent' => 'Агент',
            'idClient' => 'Клиент',
            'area' => 'Район',
            'street' => 'Улица',
            'metro' => 'Метро',
            'roomCount' => 'Комнат',
            'kitchen' => 'Кухня',
            'floor' => 'Этаж',
            'floorCount' => 'Этажность',
            'landArea' => 'Площадь',
            'fullLandArea' => 'Общ. пл',
            'priceFrom' => 'Цена от',
            'priceTo' => 'Цена до',
            'forDelete' => 'Удален',
        ];
    }

    public function getAgent() {
        return $this->hasOne(Users::className(), ['id' => 'idAgent']);
    }

    public function getClient() {
        return $this->hasOne(Client::className(), ['id' => 'idClient']);
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'idCategory']);
    }

    public function getRealty() {
        return $this->hasMany(Realty::className(), ['id' => 'idRealty'])->viaTable(Consilience::tableName(), ['idOrder' => 'id']);
    }
}
