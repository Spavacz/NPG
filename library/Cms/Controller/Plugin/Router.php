<?php

class Cms_Controller_Plugin_Router extends Zend_Controller_Plugin_Abstract
{

	/**
	 * Set the Rewrite Routes
	 *
	 * @return Cms_Controller_Plugin_Router
	 */
	public function routeStartup($request)
	{
		$front = Zend_Controller_Front::getInstance();
		$router = $front->getRouter();

		//$router->removeDefaultRoutes();
		// pobieram reguly z bazy
		$pagesMapper = new Cms_Db_Mapper_Page();
		//$iterator = new RecursiveIteratorIterator($pagesMapper->fetchAll(), RecursiveIteratorIterator::SELF_FIRST);
		// dodaje reguly
		foreach ($pagesMapper->fetchAll() as $page)
		{
			$params = array(
				'controller' => $page->getController(),
				'action' => $page->getAction(),
				'page' => $page
			);
			$pageParams = $page->getParams();

			$route = $page->getUri();
			if (is_array($pageParams))
			{
				$params = array_merge($params, $pageParams);
				foreach ($pageParams as $paramName => $v)
				{
					if(strpos($paramName, '_') !== 0)
					{
						$route .= ':' . $paramName . '/';
					}
				}
				$route .= '*';
			}
			$router->addRoute($page->getUri(),
				new Zend_Controller_Router_Route($route, $params)
			);
		}

		// regula rest
		$router->addRoute(
			'rest',
			new Zend_Rest_Route($front, array('page' => 'cms'), array('rest'))
		);

		// regula panelu admina
		$router->addRoute(
			'cms',
			new Zend_Controller_Router_Route('cms/:controller/:action/*', array(
				'module' => 'cms',
				'controller' => 'index',
				'action' => 'index',
				'page' => 'cms'
			))
		);

		// regula inteface json
		$router->addRoute(
			'json',
			new Zend_Controller_Router_Route('json/:controller/:action/*', array(
				'module' => 'json',
				'controller' => 'index',
				'action' => 'index',
				'page' => 'cms'
			))
		);
	}

}