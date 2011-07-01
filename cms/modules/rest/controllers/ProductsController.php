<?php

class Rest_ProductsController extends Zend_Rest_Controller
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
		$sort = $this->_getParam('sort', 'id');
		$order = $this->_getParam('order', 'desc');
		$page = $this->_getParam('page', 1);
		$limit = $this->_getParam('limit');

		$where = 'type = ' . $type;

		// @todo search - do optymalizacji, dane do prostej listy mozna trzymac odrazu w lucene
		//$index = Zend_Search_Lucene::create( APPLICATION_PATH . '/../data/index-products' );
		$search = $this->_getParam('search');
		if ($search)
		{
			$results = array();
			$where .= ' AND name LIKE \'%' . $search . '%\'';
		}
		
		$list = $mapper->fetchAll($where, $sort . ' ' . $order, $limit, $page);


		$products = array();
		foreach ($list as $i => $product)
		{
			$baseurl = new Zend_View_Helper_BaseUrl();
			$products[$i]['cols'] = $product->getOptions();
			$products[$i]['cols']['description'] = $this->view->truncate($product->getDescription());
			$products[$i]['cols']['status'] = $products[$i]['cols']['public'] == 1 ? $baseurl->baseUrl('admin/images/icons/ok.png')
					: $baseurl->baseUrl('admin/images/icons/delete.png');
			$products[$i]['ctrl'] = array(
				'delete' => array(
					'url' => '#delete',
					'label' => 'Usuń',
					'img' => $baseurl->baseUrl('admin/images/icons/delete.png'),
					'css' => 'delete-btn'
				),
				'edit' => array(
					'url' => $product->getUrl('cms-edit'),
					'label' => 'Edytuj',
					'img' => $baseurl->baseUrl('admin/images/icons/edit.png'),
					'css' => 'edit-btn'
				),
				'show' => array(
					'url' => $product->getUrl(),
					'label' => 'Podgląd',
					'img' => $baseurl->baseUrl('admin/images/icons/show.png'),
					'css' => 'show-btn'
				)
			);
		}
		$response = array('success' => true, 'data' => $products);
		echo json_encode($response);
	}

	public function getAction()
	{
		$id = $this->_getParam('id');
		$mapper = new Cms_Db_Mapper_Item_Product();

		if (!$mapper->find($id, $product = new Cms_Model_Item_Product()))
		{
			$response = array('success' => false);
		}
		else
		{
			if ($product->getStatus() != 0)
			{
				$data = array(
					'id' => $product->getId(),
					'name' => $product->getName(),
					'description' => $product->getDescription(),
					'parameters' => array()
				);
				foreach ($product->getParameters() as $parameter)
				{
					$data['parameters'][] = array(
						'id' => $parameter->getId(),
						'value' => $parameter->getValue()
					);
				}
				$response = array('success' => true, 'data' => $data);
			}
			else
			{
				$response = array('success' => false);
			}
		}
		echo json_encode($response);
	}

	public function putAction()
	{

	}

	public function postAction()
	{
		$data = array(
			'id' => $this->_getParam('id', null),
			'name' => $this->_getParam('name', null),
			'description' => $this->_getParam('description', null),
			'parameters' => array()
		);
		$parametersId = $this->_getParam('parameterId', array());
		$parametersValue = $this->_getParam('parameterValue', array());
		foreach ($parametersId as $i => $idParameter)
		{
			$data['parameters'][] = Cms_Model_Item_Parameter::factory($idParameter, array('value' => $parametersValue[$i]));
		}

		$product = new Cms_Model_Item_Product($data);
		$products = new Cms_Db_Mapper_Item_Product();
		if ($products->save($product) !== false)
		{
			$response = array('success' => true, 'data' => array('id' => $product->getId()));
		}
		else
		{
			$response = array('success' => false);
		}

		echo json_encode($response);
	}

	public function deleteAction()
	{
		$id = $this->_getParam('id');
		$mapper = new Cms_Db_Mapper_Item_Product();
		if ($mapper->find($id, $product = new Cms_Model_Item_Product()))
		{
			$mapper->delete($product);
			$response = array('success' => true);
		}
		else
		{
			$response = array('success' => false);
		}

		echo json_encode($response);
	}

}