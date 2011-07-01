<?php

class Rest_ParametersController extends Zend_Rest_Controller
{

	public function init()
	{
		// nie chcemy widokow
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$mapper = new Cms_Db_Mapper_Item_Parameter();
		$sort = $this->_getParam( 'sort', 'id' );
		$order = $this->_getParam( 'order', 'desc' );
		$page = $this->_getParam( 'page', 1 );
		$limit = $this->_getParam( 'limit' );
		$list = $mapper->fetchAll(null, $sort.' '.$order, $limit, $page);
		
		$parameters = array();
		$baseurl = new Zend_View_Helper_BaseUrl();
		foreach( $list as $i => $parameter )
		{
			$parameters[$i]['cols'] = array(
				'id' 			=> $parameter->getId(),
				'name'			=> $parameter->getName(),
				'type'			=> $parameter->getType(),
				'description'	=> $parameter->getDescription(),
				'options'		=> $parameter->getOptionsValues()
			);
			$parameters[$i]['ctrl'] = array(
				'edit'		=> array(
					'url'	=> $baseurl->baseUrl('cms/parameters/edit/id/' . $parameter->getId()),
					'label'	=> 'Edytuj',
					'img'	=> $baseurl->baseUrl('admin/images/icons/edit.png'),
					'css'	=> 'edit-btn'
				),
				'delete'	=> array(
					'url'	=> '#delete',
					'label'	=> 'Usuń',
					'img'	=> $baseurl->baseUrl('admin/images/icons/delete.png'),
					'css'	=> 'delete-btn'
				)
			);
		}
		
		$response = array( 'success' => true, 'data' => $parameters );
		
		echo json_encode($response);
	}

	public function getAction()
	{
		$id = $this->_getParam('id');
		$mapper = new Cms_Db_Mapper_Item_Parameter();

		if( !$mapper->find( $id, $parameter ) )
		{
			$response = array( 'success' => false );
		}
		else
		{
			$data = array(
				'id' 			=> $parameter->getId(),
				'type' 			=> $parameter->getType(),
				'name'			=> $parameter->getName(),
				'description'	=> $parameter->getDescription(),
				'options'		=> $parameter->getOptionsValues()
			);
			$response = array( 'success' => true, 'data' => $data );
		}
		echo json_encode($response);
	}

	public function putAction(){}

	public function postAction()
	{
		// dodaje nowy parametr
    	$data = array(
			'id'			=> $this->_getParam('id'),
			'type'			=> $this->_getParam('type'),
			'name'			=> $this->_getParam('name'),
			'description'	=> $this->_getParam('description')
		);

		// opcje parametru
		$optionsId = $this->_getParam('optionsId', array());
		$optionsValue = $this->_getParam('optionsValue', array());
		$data['optionsValues'] = array();
		foreach( $optionsId as $index => $id )
		{
			if( !empty($optionsValue[$index]) )
			{
				$data['optionsValues'][$index] = array(
					'id'	=> $id,
					'value' => $optionsValue[$index]
				);
			}
		}

		// tworzymy parametr
		$className = 'Cms_Model_Item_Parameter_' . $data['type'];
		$parameter = new $className( $data );
		$parameters = new Cms_Db_Mapper_Item_Parameter();

		// zapisujemy
		if( $parameters->save($parameter) !== false )
		{
			$response = array('success' => true, 'data' => array('id' => $parameter->getId()) );
		}
		else
		{
			$response = array('success' => false);
		}

		// wyswietlamy odpowiedz
		echo json_encode($response);
	}

	public function deleteAction()
	{
		$id = $this->_getParam('id');
		$mapper = new Cms_Db_Mapper_Item_Parameter();
		if( $mapper->find( $id, $parameter ) )
		{
			$mapper->delete($parameter);
			$response = array( 'success' => true );
		}
		else
		{
			$response = array( 'success' => false );
		}

		echo json_encode($response);
	}

}