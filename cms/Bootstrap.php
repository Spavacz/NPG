<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initAutoload()
	{
		Zend_Loader_Autoloader::getInstance()->registerNamespace('Cms_');

		list($autoloader) = Zend_Loader_Autoloader::getInstance()->getAutoloaders();
		$autoloader->addResourceTypes(array(
			'db' => array(
				'namespace' => 'Db',
				'path' => 'db',
			),
			'widget' => array(
				'namespace' => 'Widget',
				'path' => 'widgets',
			)
		));
	}

	/*protected function _initConfig()
	{
		$config = new Zend_Config($this->getOptions(), true);
		Zend_Registry::set('config', $config);
		return $config;
	}*/

	protected function _initHeaders()
	{
		// Upewniam sie ze mamy juz widok
		$this->bootstrap('View');

		// Ustawiam title
		$this->getResource('View')->headTitle()
			->setSeparator(' - ')
			->setDefaultAttachOrder(Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);
		$this->getResource('View')->headTitle()->append($this->getOption('appname'));
	}

	protected function _initRequest()
	{
		$this->bootstrap('FrontController');
		$request = new Cms_Controller_Request_Http();
		$this->getResource('FrontController')->setRequest($request);
	}

	protected function _initRouter()
	{
		// potrzebny bedzie FrontController i baza
		$this->bootstrap('FrontController');

		$this->getResource('FrontController')->registerPlugin(new Cms_Controller_Plugin_Router());
	}

	protected function _initDbEncoding()
	{
		$this->bootstrap('db');
		$db = $this->getResource('db');
		$db->query('SET NAMES utf8');
	}

	protected function _initAuth()
	{
		$this->bootstrap('db');
		$this->bootstrap('FrontController');

		$this->getResource('FrontController')
			->registerPlugin(new Cms_Controller_Plugin_Auth(Zend_Layout::getMvcInstance()));

		$user = new Cms_Model_User();
		if (Zend_Auth::getInstance()->hasIdentity())
		{
			$userMapper = new Cms_Db_Mapper_User();
			$userMapper->find(Zend_Auth::getInstance()->getIdentity()->id, $user);
		}
		Zend_Registry::set('user', $user);
	}

	protected function _initWidgets()
	{
		$this->bootstrap('FrontController');
		$this->bootstrap('Layout');
		$this->bootstrap('Auth');
		$front = $this->getResource('FrontController');
		$front->registerPlugin(new Cms_Controller_Plugin_Widgets(Zend_Layout::getMvcInstance()));
	}

	protected function _initHelpers()
	{
		// action helper
		Zend_Controller_Action_HelperBroker::addPath(
				realpath(APPLICATION_PATH . '/../library/Cms/Action/Helper'), 'Cms_Action_Helper');
		// view helper
		$this->getResource('View')->addHelperPath(
			realpath(APPLICATION_PATH . '/../library/Cms/View/Helper'), 'Cms_View_Helper');
		// scripts patch
		//$this->getResource('View')->addBasePath(realpath(APPLICATION_PATH . '/views'), 'Cms_View');
	}

	/* protected function _initLucene()
	  {
	  Zend_Search_Lucene_Analysis_Analyzer::setDefault(
	  new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive());
	  } */
	
	/*protected function _initNavigation()
	{
		$pages = new Cms_Db_Mapper_Page();
        $nav = $pages->fetchNavigation();
		Zend_Registry::set('Zend_Navigation', $nav);
	}*/
}

/* Funkcja ladnie wyswietlajaca tablice i obiekty */

function _d($data)
{
	if ('development' == APPLICATION_ENV)
	{
		// capture the output of print_r
		$out = print_r($data, true);
		// replace something like '[element] => <newline> (' with <a href="javascript:toggleDisplay('...');">...</a><div id="..." style="display: none;">
		$out = preg_replace('/([ \t]*)(\[[^\]]+\][ \t]*\=\>[ \t]*[a-z0-9 \t_]+)\n[ \t]*\(/iUe', "'\\1<a href=\"javascript:toggleDisplay(\''.(\$id = substr(md5(rand().'\\0'), 0, 7)).'\');\">\\2</a><div id=\"'.\$id.'\" style=\"display: none;\">'", $out);
		// replace ')' on its own on a new line (surrounded by whitespace is ok) with '</div>
		$out = preg_replace('/^\s*\)\s*$/m', '</div>', $out);
		$out = preg_replace('/<\/div>$/', '', $out);
		// print the javascript function toggleDisplay() and then the transformed output
		echo '<script language="Javascript">function toggleDisplay(id) { document.getElementById(id).style.display = (document.getElementById(id).style.display == "block") ? "none" : "block"; }</script>' . "\n<pre style=\"background-color: white;text-align:left;\">$out</pre>";
	}
}

function _vd($var)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}