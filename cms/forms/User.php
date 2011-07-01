<?php

class Cms_Form_User extends Zend_Form
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
		$this->setAttrib('id', 'user-form');
		
		// image
		$image = new Cms_Form_Element_Image( 'photo', array(
			'decorators'	=> $this->groupElementDecorators,
			'default'		=> 'admin/images/icons/anonymous_avatar.png',
			'modal'			=> true
		) );
		$this->addElement( $image );
		
		$this->addElement( 'text', 'name', array(
			'class'			=> 'medium required',
			'decorators'	=> $this->inputGroupDecorators,
			'label'			=> 'Imię',
			'filters'		=> array('StringTrim'),
			'validators'	=> array(
				array('StringLength', false, array(1,255))
			),
			'required'		=> true
		));
		
		$this->addElement( 'text', 'surname', array(
			'class'			=> 'medium required',
			'decorators'	=> $this->inputGroupDecorators,
			'label'			=> 'Nazwisko',
			'filters'		=> array('StringTrim'),
			'validators'	=> array(
				array('StringLength', false, array(1,255))
			),
			'required'		=> true
		));
		
		
		$this->addElement( 'text', 'email', array(
			'class'			=> 'medium required',
			'decorators'	=> $this->inputGroupDecorators,
			'label'			=> 'E-mail',
			'filters'		=> array('StringTrim'),
			'validators'	=> array(
				array('EmailAddress')
			),
			'required'		=> true
		));

		$this->addElement( 'text', 'phone', array(
			'class'			=> 'medium',
			'decorators'	=> $this->inputGroupDecorators,
			'label'			=> 'Telefon',
			'filters'		=> array('StringTrim'),
			'required'		=> false
		));

		$this->addElement( 'password', 'password', array(
			'class'			=> 'medium',
			'decorators'	=> $this->inputGroupDecorators,
			'label'			=> 'Hasło',
			'filters'		=> array('StringTrim'),
			'required'		=> false
		));				

		$this->addElement( 'checkbox', 'agent', array(
			'class'			=> 'checkbox',
			'decorators'	=> $this->inputGroupDecorators,
			'label'			=> 'Agent',
			'required'		=> false
		));		
		
		$sep = new Cms_Form_Element_Separator( 'sep', array(
			'decorators'	=> $this->sepElementDecorators
		) );
		$this->addElement( $sep );
		
		$this->addElement( 'textarea', 'description', array(
			'class'			=> 'full-width ckeditor',
			'decorators'	=> $this->inputElementDecorators,
			'rows'			=> 4,
			'label'			=> 'Opis',
			'filters'		=> array('StringTrim'),
			'required'		=> false
		));
		
		
		
		$this->addElement( 'submit', 'save', array(
			'class'			=> 'button big',
			'label'			=> 'Zapisz',
			'decorators'	=> $this->buttonElementDecorators
		));
		
		
		$this->addElement( 'button', 'cancel', array(
			'class'			=> 'button big',
			'label'			=> 'Anuluj',
			'decorators'	=> $this->buttonElementDecorators
		));

		
		
	}
}