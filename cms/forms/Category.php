<?php

class Cms_Form_Category extends Zend_Form
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

	public $sepDecorators = array(
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
		$this->setAttrib('id', 'category-form');

		// id
		$this->addElement('hidden', 'id', array(
			'decorators'	=> $this->inputDecorators
		));

		// idParent
		$this->addElement('hidden', 'idParent', array(
			'decorators'	=> $this->inputDecorators,
			'required'	=> true
		));

		// image
		$image = new Cms_Form_Element_Image( 'image', array(
			'decorators'	=> $this->imageDecorators,
			'default'		=> 'admin/images/icons/category.png',
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

		$sep = new Cms_Form_Element_Separator( 'sep', array(
			'decorators'	=> $this->sepDecorators
		) );
		$this->addElement( $sep );
		
		$this->addElement( 'textarea', 'description', array(
			'class'			=> 'full-width ckeditor-basic',
			'decorators'	=> $this->inputDecorators,
			'rows'			=> 4,
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