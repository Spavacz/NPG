<?php

class Cms_Form_Parameter extends Zend_Form
{
	public $inputElementDecorators = array(
        'ViewHelper',
        'Errors',
       	'Label',
    );
	public $buttonElementDecorators = array(
        'ViewHelper'
    );

	public $groupElementDecorators = array(
    	'FormElements',
		'Label'
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
		$this->setAttrib('id', 'parameter-form');
		
		// name
		$this->addElement( 'text', 'name', array(
			'class'			=> 'full-width required',
			'decorators'	=> $this->inputElementDecorators,
			'label'			=> 'Nazwa',
			'filters'		=> array('StringTrim'),
			'validators'	=> array(
				array('StringLength', false, array(1,255))
			),
			'required'		=> true
		));
		
		// description
		$this->addElement( 'textarea', 'description', array(
			'class'			=> 'full-width',
			'decorators'	=> $this->inputElementDecorators,
			'rows'			=> 2,
			'label'			=> 'Opis',
			'filters'		=> array('StringTrim'),
			'validators'	=> array(
				array('StringLength', false, array(1,300))
			),
			'required'		=> false
		));
		
		// type
		$this->addElement( 'select', 'type', array(
			'multiOptions' => array('checkbox' => 'Checkbox', 'select' => 'Select'),
			'decorators'	=> $this->inputElementDecorators,
			'label'			=> 'Rodzaj',
			'required'		=> true,
			'class'			=> 'required'
		));
		
		// options list
		$baseUrl = new Zend_View_Helper_BaseUrl();
		$options = new Zend_Form_Element('options', array(
			'Label'			=> 'Wartości parametru',
			'Description'	=> '<a href="#add-option" id="add-option">dodaj wartość <img src="' . $baseUrl->baseUrl('admin/images/icons/add.png') . '" alt="Dodaj wartość"></a>',
			'decorators'	=> array(
				array('HtmlTag', array('tag' => 'div', 'id' => 'options-list', 'class' => 'options-list')),
				'Label',
				array('Description', array('escape' => false, 'tag' => false)),
				array(
					'decorator' => array('Wrap' => 'HtmlTag'),
					'options' => array('tag' => 'div', 'class' => 'options-box')
				)
			)
		));
		$this->addElement($options);

		// category
		$this->addElement( 'select', 'category', array(
			'multiOptions' => array('', 'Sprzedaz', 'Wynajem'),
			'decorators'	=> $this->inputElementDecorators,
			'label'			=> 'Sprzedaż / Wynajem',
			'required'		=> false
		));

		// icon
		$icon = new Cms_Form_Element_Image( 'icon', array(
			'decorators'	=> $this->groupElementDecorators,
			'label'			=> 'Obrazek',
			'default'		=> 'images/blank.gif'
		) );
		$this->addElement( $icon );
		
		// hr
		$hr = new Zend_Form_Element('hr', array(
			'decorators' => array(
				array('HtmlTag', array('tag' => 'div', 'class' => 'hr'))
			)
		));
		$this->addElement($hr);
		
		// submit
		$this->addElement( 'submit', 'save', array(
			'class'			=> 'button big',
			'label'			=> 'Zapisz',
			'decorators'	=> $this->buttonElementDecorators
		));
		
		// cancel
		$this->addElement( 'button', 'cancel', array(
			'class'			=> 'button big',
			'label'			=> 'Anuluj',
			'decorators'	=> $this->buttonElementDecorators
		));
		
	}
}