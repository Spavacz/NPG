<?php

/**
 * Product Widgets
 *
 * @author Spav
 */
class Cms_Widget_Products extends Cms_Widget
{

	public function quickSearchAction()
	{
		$type = $this->getParam('type', 'sale');
		$type = $type == 'sale' ? Cms_Model_Item_Product::TYPE_SELL : Cms_Model_Item_Product::TYPE_RENT;

		// price min/max/step
		$this->view()->priceMin = 0;
		if ($type == Cms_Model_Item_Product::TYPE_SELL) {
			$this->view()->priceMax = 5000000;
			$this->view()->priceStep = 50000;
		} else {
			$this->view()->priceMax = 50000;
			$this->view()->priceStep = 1000;
		}
		$section = $this->getParam('section', 1);
		$categories = new Cms_Db_Mapper_Category();
		$this->view()->categories = $categories->fetchAll('idParent = 0', 'priority ASC');

		$mapper = new Cms_Db_Mapper_Item_Product();
		$where = array(
			'type = ' . $type,
			'section = ' . $section
		);
		$result = $mapper->findFast('COUNT(*)', implode(' AND ', $where));
		$this->view()->totalCount = $result[0];
		$this->view()->type = $type;
		$this->view()->section = $section;
		$this->view()->sectionName = Cms_Model_Item_Product::$sections[$section];
		$this->view()->searchUrl = strtolower($this->view()->sectionName)
		                           . ($type == Cms_Model_Item_Product::TYPE_SELL ? '/sprzedaz' : '/wynajem');
	}

	public function sliderAction()
	{
		$type = $this->getParam('type', 'sale');
		$type = $type == 'sale' ? Cms_Model_Item_Product::TYPE_SELL : Cms_Model_Item_Product::TYPE_RENT;

		$section = $this->getParam('section', 1);

		$mapper = new Cms_Db_Mapper_Item_Product();
		$this->view()->products = array(
			$mapper->fetchAll("status = 1 AND type = {$type} AND section = {$section} AND sliderPriority IS NOT NULL AND idRootCategory = 1", 'sliderPriority ASC', 5),
			$mapper->fetchAll("status = 1 AND type = {$type} AND section = {$section} AND sliderPriority IS NOT NULL AND idRootCategory = 2", 'sliderPriority ASC', 5),
			$mapper->fetchAll("status = 1 AND type = {$type} AND section = {$section} AND sliderPriority IS NOT NULL AND idRootCategory = 3", 'sliderPriority ASC', 5)
		);
	}

}