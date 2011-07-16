<?php

class LocalController extends Zend_Controller_Action
{

	protected $__miasto;
	protected $__dzielnica;
	protected $__miejsce;

	public function indexAction()
	{
		$miejsce = $this->_getParam('miejsce');
		if (empty($miejsce))
		{
			$dzielnica = $this->_getParam('dzielnica');
			if (empty($dzielnica))
			{
				$this->_forward('miasto');
			}
			else
			{
				$this->_forward('dzielnica');
			}
		}
		else
		{
			$this->_forward('miejsce');
		}
	}

	public function miastoAction()
	{
		$miasto = $this->_getParam('miasto');
		$mapper = new Cms_Db_Mapper_Category();
		$main = $mapper->fetchAll('idParent = 0', 'priority ASC');
		foreach ($main as $category)
		{
			if ($category->getName() == $miasto)
			{
				break;
			}
		}
		$this->view->main = $main;
		$this->view->category = $category;
	}

	public function dzielnicaAction()
	{
		$dzielnica = $this->_getParam('dzielnica');
		$mapper = new Cms_Db_Mapper_Category();
		if (!$mapper->find(array('name = ?' => $dzielnica), $category = new Cms_Model_Category()))
		{
			die('404');
		}
		$this->view->category = $category;
	}

	public function miejsceAction()
	{
		$miejsce = $this->_getParam('miejsce');
		$dzielnica = $this->_getParam('dzielnica');

		// miejsce moze byc nie jednoznaczne - trzeba wyszukac przez rodzica :(
		$mapper = new Cms_Db_Mapper_Category();
		if (!$mapper->find(array('name = ?' => $dzielnica), $parent = new Cms_Model_Category()))
		{
			die('404');
		}

		$where = array('name = ?' => $miejsce,
			'idParent = ?' => $parent->getId());
		if (!$mapper->find($where, $category = new Cms_Model_Category()))
		{
			die('404');
		}

		// miejsca
		$this->view->locals = $category->getLocals();
		$this->view->category = $category;
		$this->view->parent = $parent;
	}

}

