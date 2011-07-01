<?php

class Cms_CategoriesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->_helper->layout()->setLayout('admin');
    }

    public function indexAction()
    {
    	$mapper = new Cms_Db_Mapper_Category();
    	$this->view->pageLimit = 10;
    	$this->view->pageTotal = ceil( $mapper->countAll() / $this->view->pageLimit );
    }

    public function addAction()
    {
    	$this->view->form = new Cms_Form_Category();
    }
    
    public function editAction()
    {
    	$this->view->form = new Cms_Form_Category();
    	$mapper = new Cms_Db_Mapper_Category();
    	if( !$mapper->find( $this->_getParam('id'), $category = new Cms_Model_Category() ) )
 		{
 			$this->_forward('error', 'error', 'default');
 		}
 		
 		$this->view->form->populate( array(
 			'name'			=> $article->getName(),
 			'description'	=> $article->getDescription()
 		) );
 		
 		$this->view->id = $this->_getParam('id');
    }
    
}

