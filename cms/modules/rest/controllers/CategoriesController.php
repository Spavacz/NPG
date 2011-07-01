<?php

class Rest_CategoriesController extends Zend_Rest_Controller
{

	public function init()
	{
		// nie chcemy widokow
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$mapper = new Cms_Db_Mapper_Category();
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
		$mapper = new Cms_Db_Mapper_Category();
		if (!$mapper->find(intval($id), $category = new Cms_Model_Category()))
		{
			$response = array('success' => false);
		}
		else
		{
			$data = $category->getOptions();
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
			'idParent' => $this->_getParam('idParent'),
			'image' => $this->_getParam('image'),
			'name' => $this->_getParam('name'),
			'description' => $this->_getParam('description')
		);
		$mapper = new Cms_Db_Mapper_Category();

		// sprawdzam czy to edycja
		if (!empty($data['id']) && ctype_digit($data['id']))
		{
			if (!$mapper->find($data['id'], $category = new Cms_Model_Category()))
			{
				$response = array('success' => false);
			}
		}
		else
		{
			$category = new Cms_Model_Category();
		}

		if (!isset($response))
		{
			$category->setOptions($data);
			if ($mapper->save($category) !== false)
			{
				$response = array('success' => true, 'data' => array('id' => $category->getId()));
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
		$mapper = new Cms_Db_Mapper_Category();
		if ($mapper->find($id, $category = new Cms_Model_Category()))
		{
			$mapper->delete($category);
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