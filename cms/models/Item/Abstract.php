<?php

/**
 * 
 * Klasa reprezentujaca Item
 * @author spav
 * 
 */
abstract class Cms_Model_Item_Abstract extends Cms_Model
{

	protected $_id;
	protected $_name;
	protected $_description;
	protected $_created;
	protected $_modified;
	protected $__parameters;

	public function setId($id)
	{
		$this->_id = is_numeric($id) ? (int)$id : null;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Ustawia nazwe
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

	public function setCreated($datetime)
	{
		$this->_created = $datetime;
		return $this;
	}

	public function getCreated()
	{
		return $this->_created;
	}

	public function setModified($datetime)
	{
		$this->_modified = $datetime;
		return $this;
	}

	public function getModified()
	{
		return $this->_modified;
	}

	public function setParameters($parameters)
	{
		$this->__parameters = $parameters;
		return $this;
	}

	/**
	 * Zwraca liste parametrow
	 * @param bool $special jesli true zwraca tylko te z obrazkiem, false tylko te bez, null - wszystkie
	 * @return array
	 */
	public function getParameters($special = null)
	{
		if (!is_array($this->__parameters) && $this->getId())
		{
			$mapper = new Cms_Db_Mapper_Item_Parameter();
			$parameters = $mapper->fetchByItemId($this->getId());
			$this->setParameters($parameters);
		}

		if ($special == true)
		{
			$params = array();
			foreach ($this->__parameters as $param)
			{
				if ($param->getIcon())
				{
					$params[] = $param;
				}
			}
		}
		elseif ($special === false)
		{
			$params = array();
			foreach ($this->__parameters as $param)
			{
				if (!$param->getIcon())
				{
					$params[] = $param;
				}
			}
		}
		else
		{
			$params = $this->__parameters;
		}

		return $params;
	}

}