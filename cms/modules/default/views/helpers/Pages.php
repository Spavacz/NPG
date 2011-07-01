<?php

class Zend_View_Helper_Pages extends Zend_View_Helper_Abstract
{

	public function pages()
	{
		$pages = new Cms_Db_Mapper_Page();
		$nav = $pages->fetchNavigation();
		$this->view->navigation($nav);
		return $this->view->navigation()
			->menu()
			->setOnlyActiveBranch(true)
			->setRenderParents(false)
			->setUlClass('submenu')
			->render();
	}

}
