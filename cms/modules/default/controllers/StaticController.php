<?php

class StaticController extends Zend_Controller_Action
{

	public function mapAction()
	{

	}

	public function contactAction()
	{
		$success = false;
		$form = new Cms_Form_Contact();

		if ($this->_request->isPost())
		{

			$formData = $this->_request->getPost();
			if ($form->isValid($formData))
			{
				array_walk($formData, create_function('&$val', '$val = htmlentities($val);'));
				$formData['message'] = nl2br($formData['message']);
				if (!$this->_helper->mail('devel@spav.com.pl', 'Kontakt ze strony www.npg.pl', 'contact.phtml', $formData))
				{
					$form->addError('Przepraszamy. Nie udało się wysłać wiadomości. Napewno coś naprawiamy, próbuj ponownie za chwilę.');
				}
				$success = true;
			}
			else
			{
				$form->populate($formData);
			}
		}

		$this->view->form = $form;
		$this->view->success = $success;
	}

}
