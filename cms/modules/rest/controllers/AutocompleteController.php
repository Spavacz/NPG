<?php

class Rest_AutocompleteController extends Zend_Rest_Controller
{

	public function init()
	{
		// nie chcemy widokow
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$mapper = new Cms_Db_Mapper_Item_Product();
		$type = $this->_getParam('type', 'sale') == 'sale' ? 1 : 2;
		$query = $this->_getParam('query');
		$where .= 'type = ' . $type . ' AND name LIKE \'%' . $query . '%\'';

		$list = $mapper->findFast('name', $where, 10, 1);
		$response = array(
			'query' => $query,
			'suggestions' => $list
		);
		header('Content-type: application/json');
		echo json_encode($response);
	}

	public function getAction()
	{

	}

	public function putAction()
	{

	}

	public function postAction()
	{

	}

	public function deleteAction()
	{

	}

}