<?php

class Cms_Form_Article extends Zend_Form
{

	public $inputElementDecorators = array(
		'ViewHelper',
		'Errors',
		'Label',
	);
	public $editorElementDecorators = array(
		'ViewHelper',
		'Errors',
		'Label',
		'CKEditor'
	);
	public $buttonElementDecorators = array(
		'ViewHelper'
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
		$this->addPrefixPath('Cms_Form_Decorator', 'Cms/Form/Decorator', Zend_Form::DECORATOR);

		$this->setAttrib('id', 'article-form');

		$this->addElement('text', 'name', array(
			'class' => 'full-width required',
			'decorators' => $this->inputElementDecorators,
			'label' => 'Tytuł',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 255))
			),
			'required' => true
		));

		$this->addElement('textarea', 'description', array(
			'class' => 'full-width',
			'decorators' => $this->editorElementDecorators,
			'rows' => 2,
			'label' => 'Wstęp',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 300))
			),
			'required' => false
		));

		$this->addElement('textarea', 'text', array(
			'class' => 'full-width',
			'decorators' => $this->editorElementDecorators,
			'rows' => 10,
			'label' => 'Treść',
			'filters' => array('StringTrim'),
			'required' => false
		));

		$this->addElement('submit', 'save', array(
			'class' => 'button big',
			'label' => 'Zapisz',
			'decorators' => $this->buttonElementDecorators
		));


		$this->addElement('button', 'cancel', array(
			'class' => 'button big',
			'label' => 'Anuluj',
			'decorators' => $this->buttonElementDecorators
		));
	}

}