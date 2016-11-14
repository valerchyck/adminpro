<?php

namespace app\widgets;

use app\widgets\linkPager\LinkPager;
use yii\helpers\ArrayHelper;

class GridView extends \kartik\grid\GridView {
    public function renderPager() {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkPager */
        $pager = $this->pager;
        $class = ArrayHelper::remove($pager, 'class', LinkPager::className());
        $pager['pagination'] = $pagination;
        $pager['view'] = $this->getView();

        return $class::widget($pager);
    }
}
