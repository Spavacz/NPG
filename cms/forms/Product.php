<?php

class Cms_Form_Product extends Zend_Form
{

	protected $_category;

	public static $subtypes = array(
		'011' => 'Dom wolnostojący',
		'012' => 'Dom rekreacyjny',
		'013' => 'Dom bliźniak',
		'014' => 'Dom szeregowy',
		'015' => 'Dom kamienica',
		'021' => 'Apartament',
		'022' => 'Dobudówka',
		'023' => 'Mieszkanie',
		'031' => 'Działka',
		'032' => 'Sklep',
		'033' => 'Bióro',
		'034' => 'Magazyn'
    );
    public static $subtypeOptions = array(
        'Domy' => array(
            '011' => 'Wolnostojący',
            '012' => 'Rekreacyjny',
            '013' => 'Bliźniak',
            '014' => 'Szeregowy',
            '015' => 'Kamienica'
        ),
    	'Mieszkania' => array(
    		'021' => 'Apartament',
    		'022' => 'Dobudówka',
    		'023' => 'Mieszkanie'
    	),
        'Obiekty' => array(
            '031' => 'Działka',
            '032' => 'Sklep',
            '033' => 'Bióro',
            '034' => 'Magazyn'
        )
    );

	public function __construct($category = 1)
	{
		$this->_category = $category;
		parent::__construct();
	}

	public $inputElementDecorators = array(
		'ViewHelper',
		'Errors',
		'Label'
	);
	public $editorElementDecorators = array(
		'ViewHelper',
		'Errors',
		'Label',
		'CKEditor'
	);
	public $parametersElementDecorators = array(
		'ViewHelper',
		'Errors',
		array('Label', array('class' => 'checkbox', 'placement' => 'APPEND')),
		'Description',
		array('HtmlTag', array('tag' => 'div', 'class' => 'checkbox'))
	);
	public $groupElementDecorators = array(
		'FormElements',
		'Fieldset'
	);
	public $buttonElementDecorators = array(
		'ViewHelper'
	);
	public $hiddenDecorator = array('ViewHelper');

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
		$this->addPrefixPath('Cms_Form_Decorator', 'Cms/Form/Decorator', Zend_Form::DECORATOR);
		$this->addPrefixPath('Cms_Form_Element', 'Cms/Form/Element/', Zend_Form::ELEMENT);

		$this->setAttrib('id', 'product-form');

		$this->addElement('hidden', 'type', array(
			'required' => true,
			'decorators' => $this->hiddenDecorator
		));

		$this->addElement('text', 'name', array(
			'class' => 'medium required',
			'decorators' => $this->inputElementDecorators,
			'label' => 'Numer',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 255))
			),
			'required' => true
		));

		// publiczna
		$this->addElement('checkbox', 'public', array(
			'decorators' => $this->inputElementDecorators,
			'label' => 'Opublikowana'
		));

		// sekcja serwisu
		$this->addElement('select', 'section', array(
			'label' => 'Sekcja serwisu',
			'decorators' => $this->inputElementDecorators,
			'class' => 'medium required',
			'required' => true,
			'multioptions' => Cms_Model_Item_Product::$sections
		));
		
        // subtyp
        $this->addElement('select', 'subtype', array(
			'label' => 'Typ nieruchomości',
			'decorators' => $this->inputElementDecorators,
			'class' => 'medium required',
			'required' => true,
			'multioptions' => self::$subtypeOptions
		));

		// wczytuje kategorie
		$mapper = new Cms_Db_Mapper_Category();
		$categories = $mapper->fetchAll('idParent = 0');
		$categoryOptions = array();
		foreach ($categories as $k => $category)
		{
			$suboptions = array();
			foreach ($category->getChildren() as $subcateogry)
			{
				$suboptions[$subcateogry->getId()] = $subcateogry->getName();
			}
			$categoryOptions[$category->getName()] = $suboptions;
		}

		$this->addElement('select', 'idCategory', array(
			'label' => 'Lokalizacja',
			'decorators' => $this->inputElementDecorators,
			'class' => 'medium required',
			'required' => true,
			'multioptions' => $categoryOptions
		));

        // address
        $this->addElement('text', 'address', array(
			'class' => 'medium required',
			'decorators' => $this->inputElementDecorators,
			'label' => 'Ulica',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array(1, 255))
			),
			'required' => true
		));

		$this->addElement('text', 'price', array(
			'class' => 'required',
			'decorators' => $this->inputElementDecorators,
			'label' => 'Cena',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float')
			),
			'required' => true
		));

		$this->addElement('text', 'rooms', array(
			'class' => 'required',
			'decorators' => $this->inputElementDecorators,
			'label' => 'Ilość pokoi',
			'filters' => array('Int'),
			'validators' => array(
				array('Int')
			),
			'required' => true
		));

		$this->addElement('text', 'area', array(
			'class' => 'required',
			'decorators' => $this->inputElementDecorators,
			'label' => 'Powierzchnia (m2)',
			'filters' => array('Int'),
			'validators' => array(
				array('Int')
			),
			'required' => true
		));
		
		$this->addElement('text', 'floor', array(
			'class' => 'required',
			'decorators' => $this->inputElementDecorators,
			'label' => 'Piętro',
			'filters' => array('Int'),
			'validators' => array(
				array('Int')
			),
			'required' => true
		));
		
		// publiczna
		$this->addElement('checkbox', 'newBuilding', array(
			'decorators' => $this->inputElementDecorators,
			'label' => 'Zbudowane po 2005'
		));
		
		$this->addElement('textarea', 'description', array(
			'id' => 'description',
			'class' => 'full-width',
			'decorators' => $this->editorElementDecorators,
			'rows' => 2,
			'label' => 'Opis',
			'filters' => array('StringTrim'),
			'required' => false
		));

		// images
		$images = new Cms_Form_Element_Images('images', array(
				'decorators' => $this->groupElementDecorators,
				'legend' => 'Zdjęcia'
			));
		$this->addElement($images);

		// super gallery
		$this->addElement('checkbox', 'superGallery', array(
			'decorators' => $this->inputElementDecorators,
			'label' => 'Galeria pełnoekranowa'
		));

		// parameters
		$group = array();
		$mapper = new Cms_Db_Mapper_Item_Parameter();
		foreach ($mapper->fetchAll(array('category = 0 OR category = ?' => $this->_category)) as $parameter)
		{
			$class = 'Zend_Form_Element_' . ucfirst($parameter->getType());
			$element = new $class((string)$parameter->getId(), array(
					'label' => $parameter->getName(),
					'description' => $parameter->getDescription(),
					'belongsTo' => 'parameter',
					'decorators' => $this->parametersElementDecorators,
					'class' => 'checkbox-input'
				));
			if (count($parameter->getOptionsValues()))
			{
				$element->addMultiOption('');
				foreach ($parameter->getOptionsValues() as $option)
				{
					$element->addMultiOption($option['id'], $option['value']);
				}
			}
			$group[] = $parameter->getId();
			$this->addElement($element);
		}
		$this->addDisplayGroup($group, 'parameters', array(
			'decorators' => $this->groupElementDecorators,
			'legend' => 'Parametry'
		));


		$priorityOptions = array('' => 'brak');
		for($i = 0; $i < 21; $i++) {
			$priorityOptions[$i] = $i;
		}
		$this->addElement('select', 'sliderPriority', array(
			'label' => 'Priorirytet w rotatorze',
			'decorators' => $this->inputElementDecorators,
			'class' => 'short',
			'multioptions' => $priorityOptions
		));

		$sep = new Cms_Form_Element_Separator( 'sep', array(
			'decorators'	=> $this->sepDecorators
		) );
		$this->addElement( $sep );

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