<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user_realty".
 *
 * @property integer $id
 * @property integer $idAgent
 * @property integer $idCategory
 * @property string $folder
 * @property string $date
 * @property string $city
 * @property string $area
 * @property string $street
 * @property string $metro
 * @property integer $roomCount
 * @property string $furniture
 * @property string $state
 * @property string $feature
 * @property string $kitchen
 * @property string $limit
 * @property double $fullLandArea
 * @property double $landArea
 * @property integer $floor
 * @property integer $floorCount
 * @property double $price
 * @property integer $fphone
 * @property integer $sphone
 * @property integer $tphone
 * @property integer $frphone
 * @property string $url
 * @property string $text
 * @property integer $forDelete
 * @property Category $category
 * @property Users $agent
 * @property array $photos
 */

class UserRealty extends \yii\db\ActiveRecord {
    /**
     * @var UploadedFile[]
     */
    public $images;

    public static function tableName() {
        return 'user_realty';
    }

    public function init() {
        parent::init();

        $this->setAttributes([
            'idAgent' => \Yii::$app->user->id,
            'folder'  => \Yii::$app->security->generateRandomString(),
        ]);
    }

    public function rules() {
        return [
            [['idAgent', 'idCategory', 'roomCount', 'floor', 'floorCount', 'fphone', 'images',
                'area', 'street', 'metro', 'fullLandArea', 'landArea', 'price'], 'required'],
            [['idAgent', 'idCategory', 'roomCount', 'floor', 'floorCount',
	            'fphone', 'sphone', 'tphone', 'frphone', 'forDelete'], 'integer'],
            [['date'], 'safe'],
            [['furniture', 'state', 'feature', 'url', 'text', 'folder'], 'string'],
            [['fullLandArea', 'landArea', 'price'], 'number'],
            [['city'], 'string', 'max' => 100],
            [['area', 'street', 'metro'], 'string', 'max' => 150],
            [['kitchen', 'limit'], 'string', 'max' => 255],
            [['images'], 'file', 'extensions' => 'png, jpg', 'maxFiles' => 5],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'Ид',
            'idAgent' => 'Агент',
            'idCategory' => 'Раздел',
            'images' => 'Фото (максимум 5)',
            'date' => 'Дата',
            'city' => 'Город',
            'area' => 'Район',
            'street' => 'Улица',
            'metro' => 'Метро',
            'roomCount' => 'Комнат',
            'furniture' => 'Мебель',
            'state' => 'Состояние',
            'feature' => 'Характеристика',
            'kitchen' => 'Кухня',
            'limit' => 'Срок',
            'fullLandArea' => 'Общ. пл',
            'landArea' => 'Площадь',
            'floor' => 'Этаж',
            'floorCount' => 'Этажность',
            'price' => 'Цена',
            'fphone' => 'Телефон',
            'sphone' => 'Телефон',
            'tphone' => 'Телефон',
            'frphone' => 'Телефон',
            'url' => 'Ссылка',
            'text' => 'Текст',
        ];
    }

    public function beforeSave($insert) {
        if ($insert)
            $this->date = date('Y-m-d');
        
        return parent::beforeSave($insert);
    }

    public function upload() {
        $path = "upload/{$this->folder}/";
        FileHelper::createDirectory($path);

        foreach ($this->images as $img)
            $img->saveAs($path . $img->baseName . '.' . $img->extension);

        $this->images = null;
    }

    public function getPhotos() {
        $path  = "upload/{$this->folder}/";
        if (!file_exists($path))
            return [];

        $files = FileHelper::findFiles($path);
        foreach ($files as & $file) {
            $file = \Yii::$app->request->hostInfo.'/'.$path.pathinfo($file)['basename'];
        }

        return $files;
    }

    public function search($query, $params) {
        /**
         * @var ActiveQuery $query
         */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->session->get('page-size') == null ? 20 : \Yii::$app->session->get('page-size'),
            ],
        ]);

        if (isset($params['UserRealty'])) {
            foreach ($params['UserRealty'] as $key => $item) {
                if ($item == null || ($attr = self::getTableSchema()->getColumn($key)) === null)
                    continue;

                if (strpos($item, ';') != false) {
                    $words = explode(';', $item);
                    $where = [];
                    foreach ($words as $value) {
                        $value = trim($value);
                        $where[] = "(userRealty.$key like '$value%')";
                    }
                    $query->andOnCondition(implode(' or ', $where));
                }
                else {
                    if (Realty::isNumber($attr)) {
                        if (strpos($item, '|') != false) {
                            $words = explode('|', $item);
                            $from = trim($words[0]);
                            $to = trim($words[1]);

                            if ($key == 'price') {
                                $from .= '000';
                                $to .= '000';
                            }

                            $query->andWhere("userRealty.$key between $from and $to");
                        }
                        else {
                            $query->andWhere("userRealty.$key like '$item%'");
                        }
                    }
                    else {
                        $query->andWhere("userRealty.$key like '%$item%'");
                    }
                }
            }
        }

        return $dataProvider;
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'idCategory']);
    }

    public function getAgent() {
        return $this->hasOne(Users::className(), ['id' => 'idAgent']);
    }
}
