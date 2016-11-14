<?php

namespace app\widgets\statistics;

use app\models\Realty;
use app\models\Task;
use app\models\Users;
use yii\base\Widget;

class Statistics extends Widget{
    public $idCategory;
    public $isHot;
    public $fields;

    public function run() {
        StatisticsAsset::register($this->getView());

	    if ($this->fields == null) {
		    $this->fields = [
			    'active',
			    'finish',
			    'comments',
		    ];
	    }

        list($active, $finish, $comments, $workers) = null;
        foreach ($this->fields as $item) {
            switch ($item) {
                case 'active':
                    $active = Task::getStatistics(0, $this->idCategory, $this->isHot);
                    break;
                case 'finish':
                    $finish = Task::getStatistics(1, $this->idCategory, $this->isHot);
                    break;
                case 'comments':
                    $comments = Realty::getComments($this->idCategory, $this->isHot);
                    break;
            }
        }

        return $this->render('index', [
            'active' => $active,
            'finish' => $finish,
            'comments' => $comments,
            'idCategory' => $this->idCategory,
            'isHot' => $this->isHot,
        ]);
    }
}
