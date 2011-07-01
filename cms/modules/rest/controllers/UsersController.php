<?php

class Rest_UsersController extends Zend_Rest_Controller
{

	public function init()
	{
		// nie chcemy widokow
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$mapper = new Cms_Db_Mapper_User();
		$sort = $this->_getParam( 'sort', 'id' );
		$order = $this->_getParam( 'order', 'desc' );
		$page = $this->_getParam( 'page', 1 );
		$limit = $this->_getParam( 'limit' );
		$list = $mapper->fetchAll(null, $sort.' '.$order, $limit, $page);
		$users = array();
		$baseurl = new Zend_View_Helper_BaseUrl();
		foreach( $list as $i => $user )
		{	
			$users[$i]['cols'] = $user->toArray();
			$users[$i]['cols']['agent'] = $users[$i]['cols']['agent'] == 1 ? $baseurl->baseUrl('admin/images/icons/ok.png') : $baseurl->baseUrl('admin/images/icons/delete.png');
			$users[$i]['ctrl'] = array(
				'delete'	=> array(
					'url'	=> '#delete',
					'label'	=> 'Usuń',
					'img'	=> $baseurl->baseUrl('admin/images/icons/delete.png'),
					'css'	=> 'delete-btn'
				),
				'edit'		=> array(
					'url'	=> $baseurl->baseUrl('cms/users/edit/id/' . $user->getId()),
					'label'	=> 'Edytuj',
					'img'	=> $baseurl->baseUrl('admin/images/icons/edit.png'),
					'css'	=> 'edit-btn'
				)
			);
		}
		$response = array( 'success' => true, 'data' => $users );
		echo json_encode($response);
	}

	public function getAction(){}
	
	public function putAction(){}

	public function postAction(){}
	
	public function deleteAction(){}
}