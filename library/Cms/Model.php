<?php

class Cms_Model
{

	

	public function __construct($options = null)
	{
		$this->setOptions($options);
	}

	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method))
		{
			throw new Exception('Invalid page property');
		}
		$this->$method($value);
	}

	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method))
		{
			throw new Exception('Invalid page property');
		}
		return $this->$method();
	}

	public function setOptions($options)
	{
		if (is_array($options) || $options instanceof ArrayAccess)
		{
			$methods = get_class_methods($this);
			foreach ($options as $key => $value)
			{
				$method = 'set' . ucfirst($key);
				if (in_array($method, $methods))
				{
					$this->$method($value);
				}
			}
		}
		return $this;
	}

	public function getOptions()
	{
		$reflect = new Zend_Reflection_Class($this);
		$properties = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);
		$options = array();
		foreach ($properties as $property)
		{
			$name = $property->getName();
			if (strpos($name, '_') === 0 && strpos($name, '__') !== 0)
			{
				$rname = substr($name, 1);
				$options[$rname] = $this->{'get' . ucfirst($rname)} ();
			}
		}
		return $options;
	}

}