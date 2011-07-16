<?php

/**
 * Product Widgets
 *
 * @author Spav
 */
class Cms_Widget_Users extends Cms_Widget
{

	public function agentsAction()
	{
		$mapper = new Cms_Db_Mapper_User();
        $agents = $mapper->fetchAll('agent = 1', 'RAND()', 3);
		$this->view()->agents = $agents;
	}

	public function autorAction()
	{
		$name = $this->getParam('nr');
        $mapper = new Cms_Db_Mapper_Item_Product();

        if(!$mapper->find($mapper->__db()->quoteInto('name = ?', $name),
            $product = new Cms_Model_Item_Product())) {
            die('404');
        }
		$this->view()->agent = $product->getAgent();
	}
}