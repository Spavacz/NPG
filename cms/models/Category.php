<?php

/**
 *
 * Klasa reprezentujaca kategorie
 * @author spav
 *
 */
class Cms_Model_Category extends Cms_Model_Item_Abstract
{

	protected $_idParent;
	protected $_image;
	protected $_priority;
	protected $__url;
	protected $__parent;

	public function setIdParent($id)
	{
		$this->_idParent = is_numeric($id) ? (int)$id : null;
		return $this;
	}

	public function getIdParent()
	{
		return $this->_idParent;
	}
	
	/**
	 * @return int
	 */
	public function getPriority() {
		return $this->_priority;
	}

	/**
	 * @param int $_priority
	 * @return Cms_Model_Category
	 */
	public function setPriority($_priority) {
		$this->_priority = $_priority;
		return $this;
	}

	public function setImage($image)
	{
		if (empty($image))
		{
			$this->_image = null;
		}
		else
		{
			$this->_image = $image;
		}
		return $this;
	}

	public function getImage()
	{
		return $this->_image;
	}

	public function hasChildren()
	{
		$mapper = new Cms_Db_Mapper_Category();
		return (bool)$mapper->countChildern($this->getId());
	}

	public function getChildren()
	{
		$mapper = new Cms_Db_Mapper_Category();
		return $mapper->fetchAll(array('idParent = ?' => $this->getId()));
	}

	public function getParent()
	{
		if (is_null($this->__parent))
		{
			$idParent = $this->getIdParent();
			if (empty($idParent))
			{
				return null;
			}
			$mapper = new Cms_Db_Mapper_Category();
			$mapper->find($idParent, $this->__parent = new Cms_Model_Category());
		}

		return $this->__parent;
	}

	public function getUrl()
	{
		if (is_null($this->__url))
		{
			$baseUrl = new Zend_View_Helper_BaseUrl();
			$url = '/' . urlencode($this->getName());
			$rotate = $this;
			while ($rotate->getIdParent())
			{
				$rotate = $rotate->getParent();
				$url = '/' . urlencode($rotate->getName()) . $url;
			}
			$url = $baseUrl->baseUrl('trojmiasto' . $url);
			$this->__url = $url;
		}
		return $this->__url;
	}

	/*	 * *****************************************8
	 * ten crap powinien raczej isc do klasy dziedziczacej - np Cms_Model_Category_Local
	 */

	public function getLocals()
	{
		$mapper = new Cms_Db_Mapper_Item_Local();
		return $mapper->fetchAll(array('idCategory = ?' => $this->getId()));
	}

}