<?php

/**
 *
 * Klasa reprezentujaca parametr
 * Parametry mozna dolaczac do czego sie chce
 * @author spav
 *
 */
abstract class Cms_Model_Item_Parameter_Abstract extends Cms_Model
{

	protected $_id;
	protected $_type;
	protected $_name;
	protected $_description;
	protected $_icon;
	protected $_category;
	protected $__value;
	protected $__optionsValues;

	public function setId($id)
	{
		$this->_id = is_numeric($id) ? (int)$id : null;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setType($type)
	{
		if (class_exists('Cms_Model_Item_Parameter_' . $type))
		{
			$this->_type = $type;
		}
		return $this;
	}

	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Ustawia nazwe parametru
	 *
	 * @param array $name
	 */
	public function setName($name)
	{
		$this->_name = !empty($name) ? $name : null;
		return $this;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function setDescription($description)
	{
		$this->_description = !empty($description) ? $description : null;
		return $this;
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function setIcon($icon)
	{
		$this->_icon = !empty($icon) ? $icon : null;
		return $this;
	}

	public function getIcon()
	{
		return $this->_icon;
	}

	/**
	 * Kategoria parametru
	 * 0 - dowlna
	 * 1 - sprzedaz
	 * 2 - wynajem
	 */
	public function setCategory($category)
	{
		$this->_category = (int)$category;
		return $this;
	}

	public function getCategory()
	{
		return (int)$this->_category;
	}

	public function setOptionsValues($options)
	{
		$this->__optionsValues = $options;
		return $this;
	}

	public function setValue($value)
	{
		$this->__value = !empty($value) ? $value : null;
		return $this;
	}

	public function getValue()
	{
		return $this->__value;
	}

	public function getOptionsValues()
	{
		if (is_null($this->__optionsValues))
		{
			$table = new Cms_Db_Table_Item_Parameters_Options();
			$this->setOptionsValues($table->fetchAll('idParameter = ' . $this->getId())->toArray());
		}
		return empty($this->__optionsValues) ? array() : $this->__optionsValues;
	}

}