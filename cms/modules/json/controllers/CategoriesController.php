<?php

/**
 * Json CategoriesController
 *
 * @author Spav
 */
class Json_CategoriesController extends Zend_Controller_Action
{

	public function init()
	{
		// nie chcemy widokow
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function autocompleteAction()
	{
		$query = $this->_getParam('search');
		$mapper = new Cms_Db_Mapper_Category();
		$hits = $mapper->fetchAll(array('status = 1 AND idParent <> 0 AND name LIKE ?' => $query . '%'));
		$result = array();
		foreach($hits as $hit) {
			/* @var $hit Cms_Model_Category */
			$result[] = array($hit->getId(), $hit->getName());
		}
		/*$mapper = new Cms_Db_Mapper_Lucene_Category();
		$hits = $mapper->find("{$query}*");
		$result = array();
		foreach($hits as $hit) {
			if($hit->id_parent != 0) {
				$result[] = array($hit->id_category, $hit->name);
			}
		}*/

		header('Content-type: application/json');
		echo json_encode($result);
	}

	public function indexAction()
	{
		
	}

}
