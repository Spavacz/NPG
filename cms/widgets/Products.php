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
		$categories = new Cms_Db_Mapper_Category();
		$this->view()->categories = $categories->fetchAll('idParent = 0');

		$mapper = new Cms_Db_Mapper_Item_Product();
		// @todo - zparametryzowac typ, narazie sell
		$result = $mapper->findFast('COUNT(*)',
				'type = ' . ($type == 'sale' ? Cms_Model_Item_Product::TYPE_SELL : Cms_Model_Item_Product::TYPE_RENT));
		$this->view()->totalCount = $result[0];
		$this->view()->type = $type;
	}

	public function sliderAction()
	{
		$type = $this->getParam('type', 'sale');
		$type = $type == 'sale' ? Cms_Model_Item_Product::TYPE_SELL : Cms_Model_Item_Product::TYPE_RENT;

		$mapper = new Cms_Db_Mapper_Item_Product();
		$this->view()->products = array(
            $mapper->fetchAll("status = 1 AND type = {$type} AND sliderPriority IS NOT NULL AND idRootCategory = 1", 'sliderPriority ASC', 5),
            $mapper->fetchAll("status = 1 AND type = {$type} AND sliderPriority IS NOT NULL AND idRootCategory = 2", 'sliderPriority ASC', 5),
            $mapper->fetchAll("status = 1 AND type = {$type} AND sliderPriority IS NOT NULL AND idRootCategory = 3", 'sliderPriority ASC', 5)
        );
	}

}