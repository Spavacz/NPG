<?php

class Rest_LocalsController extends Zend_Rest_Controller
{

	public function init()
	{
		// nie chcemy widokow
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$mapper = new Cms_Db_Mapper_Local();
		$idParent = $this->_getParam('idParent', 0);
		$sort = $this->_getParam('sort', 'id');
		$order = $this->_getParam('order', 'desc');
		$list = $mapper->fetchAll(array('idParent = ?' => $idParent), $sort . ' ' . $order);

		// url helper
		$baseurl = new Zend_View_Helper_BaseUrl();
		$categories = array();
		foreach ($list as $i => $category)
		{
			$categories[$i]['cols'] = array(
				'id' => $category->getId(),
				'idParent' => $category->getIdParent(),
				'name' => $category->getName(),
				'leaf' => !$category->hasChildren(),
				'description' => $category->getDescription()
			);
			$categories[$i]['ctrl'] = array(
				'edit' => array(
					'url' => $baseurl->baseUrl('cms/categories/edit/id/' . $category->getId()),
					'label' => 'Edytuj',
					'img' => $baseurl->baseUrl('admin/images/icons/edit.png'),
					'css' => 'edit-btn'
				),
				'delete' => array(
					'url' => '#delete',
					'label' => 'Usuń',
					'img' => $baseurl->baseUrl('admin/images/icons/delete.png'),
					'css' => 'delete-btn'
				)
			);
		}

		$response = array('success' => true, 'data' => $categories);
		header('Content-type: application/json');
		echo json_encode($response);
	}

	public function getAction()
	{
		$id = $this->_getParam('id');
		$mapper = new Cms_Db_Mapper_Item_Local();

		if (!$mapper->find(intval($id), $local = new Cms_Model_Item_Local()))
		{
			$response = array('success' => false);
		}
		else
		{
			$data = $local->getOptions();
			$response = array('success' => true, 'data' => $data);
		}
		header('Content-type: application/json');
		echo json_encode($response);
	}

	public function putAction()
	{

	}

	public function postAction()
	{
		$data = array(
			'id' => $this->_getParam('id'),
			'idCategory' => $this->_getParam('idCategory'),
			'image' => $this->_getParam('image'),
			'name' => $this->_getParam('name'),
			'description' => $this->_getParam('description'),
			'www' => $this->_getParam('www'),
			'address' => $this->_getParam('address')
		);

		$mapper = new Cms_Db_Mapper_Item_Local();

		// sprawdzam czy to edycja
		if (!empty($data['id']) && ctype_digit($data['id']))
		{
			if (!$mapper->find($data['id'], $local = new Cms_Model_Item_Local()))
			{
				$response = array('success' => false);
			}
		}
		else
		{
			$local = new Cms_Model_Item_Local();
		}

		if (!isset($response))
		{
			$local->setOptions($data);
			if ($mapper->save($local) !== false)
			{
				$response = array('success' => true, 'data' => array('id' => $local->getId()));
			}
			else
			{
				$response = array('success' => false);
			}
		}
		header('Content-type: application/json');
		echo json_encode($response);
	}

	public function deleteAction()
	{
		$id = $this->_getParam('id');
		$mapper = new Cms_Db_Mapper_Item_Local();
		if ($mapper->find($id, $local = new Cms_Model_Item_Local()))
		{
			$mapper->delete($local);
			$response = array('success' => true);
		}
		else
		{
			$response = array('success' => false);
		}
		header('Content-type: application/json');
		echo json_encode($response);
	}

}