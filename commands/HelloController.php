<?php
namespace app\commands;

use app\models\ArrayHelper;
use yii\base\ErrorException;
use yii\console\Controller;

class HelloController extends Controller {
    public function actionDiff() {
        $result = [];
        $values = [];
        exec("git diff --name-only", $result);

        foreach ($result as $item) {
            $values[] = "('$item')";
        }
        $values = implode(',', $values);

        \Yii::$app->db->createCommand("INSERT INTO `changed_files`(`filename`) VALUES $values ON DUPLICATE KEY UPDATE `status` = 0")->execute();

        echo "finish\n";
    }

    public function actionSendFtp() {
        $config = require_once \Yii::getAlias('@app').'/config/ftp.php';
        $result = \Yii::$app->db->createCommand("SELECT `filename` FROM `changed_files`")->queryAll();
        if ($result == null)
            die("empty\n");

        $connect = ftp_connect($config['host']);
        ftp_login($connect, $config['login'], $config['password']);

        foreach (ArrayHelper::getColumn($result, 'filename') as $item) {
            $res = ftp_put($connect, "/$item", \Yii::getAlias('@app').'/'.$item, FTP_BINARY);
            if (!$res)
                die("Ошибка при загрузке $item\n");

            echo "Файл $item загружен\n";
        }

	    \Yii::$app->db->createCommand("UPDATE `changed_files` SET `status` = 1")->execute();
        die("\nВсе файлы были успешно загружены\n");
    }
}
