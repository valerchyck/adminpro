<?php

namespace app\models;

class ArrayHelper extends \yii\helpers\ArrayHelper {
	public static function count(array $array, $callback) {
		$count = 0;
		foreach($array as $value) {
			if($callback($value)) {
				$count++;
			}
		}
		return $count;
	}

	private static function isFound($value, $condition, $strict)
	{
		if(is_callable($condition)) {
			return $condition($value);
		}
		elseif(is_array($condition)) {
			foreach($condition as $key=>$con) {
				$column = self::getValue($value, $key);
				if(!self::isFound($column, $con, $strict)) {
					return false;
				}
			}
			return true;
		}
		else {
			if ($strict)
				return $value === $condition;
			return $value == $condition;
		}
	}

	/**
	 * @param array $array
	 * @param callable|array|mixed $condition
	 * @param bool $strict is ===
	 *
	 * @return mixed|null
	 */
	public static function findOne(array $array, $condition, $strict = true) {
		foreach($array as $value) {
			if(self::isFound($value,$condition, $strict)) {
				return $value;
			}
		}
		return null;
	}

	/**
	 * @param array $array
	 * @param callable|array|mixed $condition
	 * @param bool $strict is ===
	 *
	 * @return array
	 */
	public static function findAll(array $array, $condition, $strict = true) {
		$result = [];
		foreach($array as $value) {
			if(self::isFound($value,$condition, $strict)) {
				$result[] = $value;
			}
		}
		return $result;
	}

	/**
	 * @param array $array
	 * @param callable|array|mixed $condition
	 * @param bool $strict is ===
	 *
	 * @return array
	 */
	public static function range($low, $high) {
		$result = [];
		for ($i = $low; $i <= $high; $i++) {
			$result[$i] = $i;
		}

		return $result;
	}
}
