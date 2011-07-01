<?php

class Cms_Db_Mapper_Page extends Cms_Db_Mapper_Abstract
{

	protected $_dbTableName = 'Cms_Db_Table_Pages';
	protected $_modelName = 'Cms_Model_Page';

	public function fetchActive()
	{
		$page = Zend_Controller_Front::getInstance()->getRequest()->getParam('page');
		// jesli nie ma page znaczy ze rest lub cms
		if (!$page instanceof Cms_Model_Page)
		{
			$page = new Cms_Model_Page();
			$page->isCms(true);
		}
		return $page;
	}

	// @todo - to powinno byc osobna klasa - singleton
	public function fetchNavigation()
	{
		$pages = $this->fetchAllAssoc();
		$nav = new Zend_Navigation();

		$requestUri = null;
		$activePage = Zend_Controller_Front::getInstance()->getRequest()->getParam('page');
		if ($activePage)
		{
			$requestUri = trim($activePage->getUri(), '/');
		}

		foreach ($pages as $page)
		{
			$navPage = $page->getNavigation();
			$navPage->setOrder($page->getOrder());
			if ($requestUri == trim($navPage->getUri(), '/'))
			{
				$navPage->setActive(true);
			}

			$idParent = $page->getIdParent();
			if (empty($idParent))
			{
				$nav->addPage($navPage);
			}
			else
			{
				$pages[$page->getIdParent()]->getNavigation()->addPage($navPage);
			}
		}
		return $nav;
	}

}
