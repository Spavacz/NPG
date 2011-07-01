<?php

class Cms_Form_Contact extends Zend_Form
{

	public $inputElementDecorators = array(
		'ViewHelper',
		'Errors',
		'Label',
	);
	public $inputGroupDecorators = array(
		'ViewHelper',
		'Errors',
		'Label',
		array('HtmlTag', array('tag' => 'div', 'class' => 'inputGroup'))
	);
	public $buttonElementDecorators = array(
		'ViewHelper'
	);
	public $groupElementDecorators = array(
		'FormElements'
	);
	public $sepElementDecorators = array(
		array('HtmlTag', array('tag' => 'div', 'class' => 'hr'))
	);

	public function loadDefaultDecorators()
	{
		$this->setDecorators(array(
			'FormElements',
			'Form',
		));
	}

	public function init()
	{
		$this->setAttrib('id', 'contact-form');

		$this->addElement('text', 'name',
			array(
			'class' => 'medium required',
			'decorators' => $this->inputGroupDecorators,
			'label' => 'Imię',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 255))
			),
			'required' => true
		));

		$this->addElement('text', 'surname',
			array(
			'class' => 'medium required',
			'decorators' => $this->inputGroupDecorators,
			'label' => 'Nazwisko',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 255))
			),
			'required' => true
		));

		$this->addElement('text', 'email',
			array(
			'class' => 'medium required',
			'decorators' => $this->inputGroupDecorators,
			'label' => 'E-mail',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('EmailAddress'),
				array('StringLength', false, array(1, 255))
			),
			'required' => true
		));

		$this->addElement('text', 'address',
			array(
			'class' => 'medium',
			'decorators' => $this->inputGroupDecorators,
			'label' => 'Adres',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 255))
			)
		));

		$this->addElement('text', 'city',
			array(
			'class' => 'medium',
			'decorators' => $this->inputGroupDecorators,
			'label' => 'Miasto',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 255))
			)
		));

		$this->addElement('text', 'phone',
			array(
			'class' => 'medium',
			'decorators' => $this->inputGroupDecorators,
			'label' => 'Telefon',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 255))
			),
			'required' => true
		));

		$this->addElement('textarea', 'message',
			array(
			'class' => 'full-width',
			'decorators' => $this->inputElementDecorators,
			'rows' => 4,
			'label' => 'Twoja wiadomość',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(0, 500))
			),
			'required' => false
		));

		$this->addElement('captcha', 'antirobot',
			array(
			'label' => 'Przepisz kod z obrazka',
			'captcha' => array(
				'captcha' => 'Image',
				'wordlen' => 5,
				'font' => APPLICATION_PATH . '/../data/fonts/verdana.ttf',
				'imgDir' => APPLICATION_PATH . '/../public/images/captcha/',
				'imgUrl' => '/images/captcha/'
			)
		));

		$this->addElement('submit', 'send',
			array(
			'class' => 'button big',
			'label' => 'Wyślij',
			'decorators' => $this->buttonElementDecorators
		));
	}

}