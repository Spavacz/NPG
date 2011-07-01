<?php

class Cms_ParametersController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
		$this->_helper->layout()->setLayout('admin');
	}

	public function indexAction()
	{
		$mapper = new Cms_Db_Mapper_Item_Parameter();
		$this->view->pageLimit = 10;
		$this->view->pageTotal = ceil($mapper->countAll() / $this->view->pageLimit);
	}

	public function addAction()
	{
		$this->view->form = new Cms_Form_Parameter();
	}

	public function editAction()
	{
		$this->view->form = new Cms_Form_Parameter();
		$parameter = Cms_Model_Item_Parameter::factory($this->_getParam('id'));
		if (!$parameter->getId())
		{
			$this->_forward('error', 'error', 'default');
		}
		$this->view->form->populate(array(
			'name' => $parameter->getName(),
			'description' => $parameter->getDescription(),
			'type' => $parameter->getType(),
			'icon' => $parameter->getIcon(),
			'category' => $parameter->getCategory()
			));

		$this->view->id = $this->_getParam('id');
	}

}

