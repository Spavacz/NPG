<?php

class Cms_Controller_Plugin_Widgets extends Zend_Layout_Controller_Plugin_Layout
{

	/**
	 * dispatch widgets
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{
		$response = $this->getResponse();
		$layout = $this->getLayout();
		$helper = $this->getLayoutActionHelper();
		// Return early if forward or error detected
		if (!$request->isDispatched()
			|| $response->isRedirect()
			|| ($response->isException() && $response->getHttpResponseCode() == 200) // obejscie na 404 itp
			|| ($layout->getMvcSuccessfulActionOnly()
			&& (!empty($helper) && !$helper->isActionControllerSuccessful())))
		{
			return;
		}
		// Return early if layout has been disabled
		if (!$layout->isEnabled())
		{
			return;
		}

		$view = $layout->getView();
		$page = $request->getParam('page');

		// ustawiam strone
		if ($page instanceof Cms_Model_Page)
		{
			// error page layout
			if ($response->getHttpResponseCode() !== 200)
			{
				$page->setId(-1)->setLayout('npg_right_col');
			}

			// set label
			$view->headTitle($page->getLabel()); // to powinno byc w jakims plugin_layout czy cos
			// set layout
			$layout->setLayout($page->getLayout());
			// add blocks
			foreach ($page->getBlocks() as $block)
			{
				foreach ($block->getWidgets() as $widget)
				{
					// create widget
					$className = $widget->getController();
					$widgetObject = new $className($view, $widget->getParams(), $widget->getInstanceId());
					// action call
					$widgetObject->{$widget->getAction()}();
					// render to placeholder
					$view->renderToPlaceholder($widget->getViewScript(), $block->getPlaceholder());
				}
			}
		}
		else
		{
			// dla cms nie ladujemy widgetow... narazie :>
		}
	}

}