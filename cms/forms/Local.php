<?php

class Cms_Form_Local extends Zend_Form
{
	public $inputDecorators = array(
        'ViewHelper',
        'Errors',
       	'Label',
	);

	public $imageDecorators = array(
    	'FormElements'
    );

	public $buttonDecorators = array(
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
		$this->setAttrib('id', 'local-form');

		$this->addElement( 'hidden', 'id', array(
			'decorators'	=> array('ViewHelper')
		));

		$this->addElement( 'hidden', 'idCategory', array(
			'decorators'	=> array('ViewHelper')
		));

				// image
		$image = new Cms_Form_Element_Image( 'image', array(
			'decorators'	=> $this->imageDecorators,
			'default'		=> 'admin/images/icons/local.png',
			'modal'			=> true
		) );
		$this->addElement( $image );

		$this->addElement( 'text', 'name', array(
			'class'			=> 'medium required',
			'decorators'	=> $this->inputDecorators,
			'label'			=> 'Nazwa',
			'filters'		=> array('StringTrim'),
			'validators'	=> array(
				array('StringLength', false, array(1,255))
			),
			'required'		=> true
		));

		$this->addElement( 'text', 'www', array(
			'class'			=> 'medium',
			'decorators'	=> $this->inputDecorators,
			'label'			=> 'Adres WWW',
			'filters'		=> array('StringTrim'),
		));

		$this->addElement( 'textarea', 'address', array(
			'class'			=> 'medium',
			'decorators'	=> $this->inputDecorators,
			'rows'			=> 1,
			'label'			=> 'Adres',
			'filters'		=> array('StringTrim'),
		));

		$this->addElement( 'textarea', 'description', array(
			'id'			=> 'local-description',
			'class'			=> 'full-width ckeditor-basic',
			'decorators'	=> $this->inputDecorators,
			'rows'			=> 2,
			'label'			=> 'Opis',
			'filters'		=> array('StringTrim'),
			'required'		=> false
		));

		$this->addElement( 'submit', 'save', array(
			'class'			=> 'button big',
			'label'			=> 'Zapisz',
			'decorators'	=> $this->buttonDecorators
		));
		
		
		$this->addElement( 'button', 'cancel', array(
			'class'			=> 'button big',
			'label'			=> 'Anuluj',
			'decorators'	=> $this->buttonDecorators
		));		
	}
}