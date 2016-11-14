<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\FileHelper;

class Helper extends Model {
	public static function isMainPage() {
		return \yii\helpers\Url::to('') == '/';
	}

	public static function documentRoot() {
		return \Yii::$app->basePath;
	}

	public static function checkExtension($filename, $extensions) {
		if (!is_array($extensions))
			throw new \InvalidArgumentException('second argument must be array');

		return in_array(pathinfo($filename, PATHINFO_EXTENSION), $extensions);
	}

	public static function isEmpty($value){
		if (is_array($value))
			return count($value) == 0;

		return empty($value);
	}

	public static function getProtocol() {
		return strpos(\Yii::$app->request->absoluteUrl, 'https') == false ? 'http' : 'https';
	}

	public static function isEmptyPost($vars) {
		if (!is_array($vars))
			throw new \InvalidArgumentException('data must be array');

		foreach ($vars as $item)
			if (empty($_POST[$item]))
				return true;

		return false;
	}

	public static function getServerName() {
		return self::getProtocol().'://'.\Yii::$app->request->serverName;
	}

	public static function getHash() {
		return md5(microtime(true));
	}

	public static function xlsToCsv($file) {
		$objReader = \PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcelReader = $objReader->load($file);

		$loadedSheetNames = $objPHPExcelReader->getSheetNames();
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcelReader, 'CSV');

		if (!file_exists('upload'))
			FileHelper::createDirectory('upload', 0775);

		$filename = '';
		foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
			$filename = 'upload/' . $loadedSheetName . '.csv';
			$objWriter->save($filename);
			chmod($filename, 0775);
		}

		return $filename;
	}

	/**
	 * @param $keys    array
	 * @param $columns array
	 * @return int
	 * @throws \yii\db\Exception
	 */
	public static function batchInsert($keys, & $columns) {
		$values = [];
		foreach ($keys as $key) {
			$values[] = "`$key` = VALUES(`$key`)";
		}

		$sql = \Yii::$app->db->queryBuilder->batchInsert('realty', $keys, $columns);
		$columns = [];

		return \Yii::$app->db->createCommand($sql . ' ON DUPLICATE KEY UPDATE ' . implode(',', $values))->execute();
	}
}
