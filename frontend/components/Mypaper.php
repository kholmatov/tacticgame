<?php
namespace frontend\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\base\Widget;
use yii\data\Pagination;


class Mypaper  extends \yii\widgets\LinkPager
{

	protected function renderPageButton($label, $page, $class, $disabled, $active)
	{
		$options = ['class' => $class === '' ? null : $class];
		print_r($options);
		if ($active) {
			Html::addCssClass($options, $this->activePageCssClass);
		}
		if ($disabled) {
			Html::addCssClass($options, $this->disabledPageCssClass);

			return  Html::tag('span', $label);
		}
		$linkOptions = $this->linkOptions;
		$linkOptions['data-page'] = $page;

		return Html::a($label, $this->pagination->createUrl($page), $linkOptions);
	}


	protected function renderPageButtons()
	{
		$pageCount = $this->pagination->getPageCount();
		if ($pageCount < 2 && $this->hideOnSinglePage) {
			return '';
		}

		$buttons = [];
		$currentPage = $this->pagination->getPage();

		// first page
		$firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
		if ($firstPageLabel !== false) {
			$buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
		}

		// prev page
		if ($this->prevPageLabel !== false) {
			if (($page = $currentPage - 1) < 0) {
				$page = 0;
			}

			$buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
		}

		// internal pages
		list($beginPage, $endPage) = $this->getPageRange();
		for ($i = $beginPage; $i <= $endPage; ++$i) {
			$buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
		}

		// next page
		if ($this->nextPageLabel !== false) {
			if (($page = $currentPage + 1) >= $pageCount - 1) {
				$page = $pageCount - 1;
			}
			$buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
		}

		// last page
		$lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
		if ($lastPageLabel !== false) {
			$buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
		}

		return Html::tag('div', implode("\n", $buttons), $this->options);
	}
}