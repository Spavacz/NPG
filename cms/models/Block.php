<?php

class Cms_Model_Block extends Cms_Model
{

	protected $_id;
	protected $_name;
	protected $_placeholder;
	protected $_page;
	protected $_priority;
	protected $__widgets;
	protected $_params;
	protected $__instanceId;

	public function setId($id)
	{
		$this->_id = (int)$id;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setName($name)
	{
		$this->_name = $name;
		return $this;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function setPlaceholder($placeholder)
	{
		$this->_placeholder = $placeholder;
		return $this;
	}

	public function getPlaceholder()
	{
		return $this->_placeholder;
	}

	public function setPriority($priority)
	{
		$this->_priority = (int)$priority;
		return $this;
	}

	public function getPriority()
	{
		return $this->_priority;
	}

	public function setPage($page)
	{
		$this->_page = $page;
		return $this;
	}

	public function getPage()
	{
		return $this->_page;
	}

	public function getWidgets()
	{
		if (is_null($this->__widgets))
		{
			$widgets = new Cms_Db_Mapper_Widget();
			$this->__widgets = $widgets->fetchAllInBlock($this);
		}
		return $this->__widgets;
	}

	public function getInstanceId()
	{
		return $this->__instanceId;
	}

	public function setInstanceId($instanceId)
	{
		$this->__instanceId = (int)$instanceId;
		return $this;
	}

	public function setParams($params)
	{
		if(is_string($params))
		{
			$params = unserialize($params);
		}
		$this->_params = $params;
		return $this;
	}

	public function getParams()
	{
		return $this->_params;
	}

	public function getParam($name, $default = null)
	{
		if (isset($this->_params[$name]))
		{
			return $this->_params[$name];
		}
		return $default;
	}

}
