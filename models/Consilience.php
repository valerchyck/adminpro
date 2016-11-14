<?php

namespace app\models;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\IntegrityException;

/**
 * This is the model class for table "consilience".
 *
 * @property integer $id
 * @property integer $idRealty
 * @property integer $idOrder
 */

class Consilience extends \yii\db\ActiveRecord {
    private static $validAttributes = ['area', 'street', 'metro', 'roomCount', 'kitchen', 'floor', 'floorCount', 'priceFrom', 'priceTo'];

    public static function tableName() {
        return 'consilience';
    }

    public function rules() {
        return [
            [['idRealty', 'idOrder'], 'integer']
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'Ид',
            'idRealty' => 'Объявление',
            'idOrder' => 'Заказ',
        ];
    }

    public static function compare() {
        $orders = Order::find()->all();
        $found = [];

        foreach ($orders as $order) {
            $query = Realty::find()->where(['idCategory' => $order->idCategory, 'isHot' => 1]);

            foreach ($order->getAttributes(self::$validAttributes) as $attr => $value) {
                if ($value == null)
                    continue;

                if (in_array($attr, ['priceFrom', 'priceTo'])) {
                    $query->andWhere('price between :from and :to', [':from' => $order->priceFrom, ':to' => $order->priceTo]);
                }
                else {
                    $query->andWhere([$attr => $value]);
                }
            }
            $result = $query->all();
            foreach ($result as $item) {
                $found[] = [
                    'idOrder' => $order->id,
                    'realty' => $item,
                ];
            }
        }

        foreach ($found as $item) {
            $record = new Consilience([
                'idRealty' => $item['realty']->id,
                'idOrder' => $item['idOrder'],
            ]);

            try {
                $record->save();
            }
            catch (IntegrityException $e) {}
        }
    }

    /**
     * @return ActiveQuery
     */
    public static function getFound() {
        $key = Cache::buildKey([__METHOD__]);
        $query = Cache::cache()->get($key);

        if ($query === false) {
            $query = Order::find()->innerJoin('consilience c', 'c.idOrder = order.id')->groupBy('order.id');
            if (\Yii::$app->user->identity->role == 2) {
                $agent = Users::findOne(['id' => \Yii::$app->user->identity->id]);
                $query->where(['order.idAgent' => $agent->id]);
            }

            Cache::cache()->add($key, $query, 3600);
        }

        return $query;
    }
}
