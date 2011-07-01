<?php

class Cms_UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->_helper->layout()->setLayout('admin');
    }

    public function indexAction()
    {
    	$mapper = new Cms_Db_Mapper_User();
    	$this->view->pageLimit = 10;
    	$this->view->pageTotal = ceil( $mapper->countAll() / $this->view->pageLimit );
    }

    public function addAction()
    {
    	$this->view->form = new Cms_Form_User();
    }
    
    public function editAction()
    {
    	$this->view->form = new Cms_Form_User();
    	$mapper = new Cms_Db_Mapper_User();
    	if( !$mapper->find( $this->_getParam('id'), $user = new Cms_Model_User() ) )
 		{
 			$this->_forward('error', 'error', 'default');
 		}
 		
 		$this->view->form->populate( array(
	 		'email'			=> $user->email,
 			'name'			=> $user->name,
 			'surname'		=> $user->surname,
 			'phone'			=> $user->phone,
 			'job'			=> $user->job,
 			'photo'			=> $user->photo,
 			'description'	=> $user->description,
 			'agent'			=> $user->agent
 		) );
 		
 		$this->view->id = $this->_getParam('id');
    }
    
}

