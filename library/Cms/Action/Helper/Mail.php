<?php

class Cms_Action_Helper_Mail extends Zend_Controller_Action_Helper_Abstract
{

	public function direct($recipments, $subject, $viewName, $vars = null)
	{
		$mail = new Zend_Mail('UTF-8');

		$mail->addTo($recipments);
		$mail->setSubject($subject);

		// body
		$view = new Zend_View();
		$view->setScriptPath(APPLICATION_PATH . '/layouts/mails');
		if(!is_null($vars))
		{
			$view->assign($vars);
		}
		$body = $view->render($viewName);
		$mail->setBodyHtml($body);

		try
		{
			$mail->send();
		}
		catch (Exception $e)
		{
			return false;
		}
		return true;
	}

}