<?php

class Cms_LocalController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->_helper->layout()->setLayout('admin');
    }

    public function indexAction()
    {
    	$this->view->categoryForm = new Cms_Form_Category();
		$this->view->localForm = new Cms_Form_Local();

		$mapper = new Cms_Db_Mapper_Category();
		$this->view->treeRoot = new Cms_Model_Category(array('id' => 0));
	}

}
