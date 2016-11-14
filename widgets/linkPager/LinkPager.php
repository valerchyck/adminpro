<?php

namespace app\widgets\linkPager;

use yii\helpers\Html;
use yii\helpers\Url;

class LinkPager extends \yii\widgets\LinkPager
{
    protected function renderPageInput($currentPage)
    {
        $label = Html::label('На страницу: ');
        $input = Html::textInput('page-input', $currentPage + 1, ['class' => 'form-control', 'maxlength' => 5]);
        $route = \Yii::$app->request->url;
        $params = \Yii::$app->getRequest()->getQueryParams();

        unset($params['page']);
        $url = Url::to($route, $params);

        $button = Html::buttonInput('Перейти', ['class' => 'btn btn-success', 'onclick' => "goToPage(this, '{$url}')"]);
        $div = Html::tag('div', $label . $input . $button, ['class' => 'page-input']);

        return Html::tag('li', $div);
    }

    public function run()
    {
        LinkPagerAsset::register($this->getView());
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }
        echo $this->renderPageButtons();
    }

    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // to begin
        $buttons[] = $this->renderPageButton($this->prevPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // to end
        $buttons[] = $this->renderPageButton($this->nextPageLabel, $pageCount-1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);

        // text input
        $buttons[] = $this->renderPageInput($currentPage);

        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }
}
