<?php

namespace app\models;

class Cache {
    /**
     * @return \yii\caching\Cache
     */
    public static function cache() {
        return \Yii::$app->cache;
    }

    /**
     * @param $params
     * @return string
     */
    public static function buildKey($params) {
        if (!is_array($params))
            throw new \InvalidArgumentException('params must be array');

        return self::cache()->buildKey(implode(':', $params));
    }
}
