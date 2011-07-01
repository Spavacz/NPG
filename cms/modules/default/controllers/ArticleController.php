<?php

class ArticleController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		$mapperPage = new Cms_Db_Mapper_Page();
		$mapperArticle = new Cms_Db_Mapper_Item_Article();

		$page = $mapperPage->fetchActive();
		$this->view->articles = $mapperArticle->fetchAll(array('idPage = ?' => $page->getId()));
	}

	public function viewAction()
	{
		$idArticle = $this->_getParam('key');
		$mapperArticle = new Cms_Db_Mapper_Item_Article();

		if ($mapperArticle->find($idArticle, $article = new Cms_Model_Item_Article()))
		{
			$this->view->article = $article;
		}
		else
		{
			die('404'); // @todo
		}
	}

}

