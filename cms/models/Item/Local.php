<?php

/**
 * 
 * Klasa reprezentujaca Item
 * @author spav
 * 
 */
class Cms_Model_Item_Local extends Cms_Model_Item_Abstract
{

	protected $_idCategory;
	protected $_image;
	protected $_address;
	protected $_www;
	protected $__category;

	public function setIdCategory($idCategory)
	{
		$this->_idCategory = (int)$idCategory;
		return $this;
	}

	public function getIdCategory()
	{
		return $this->_idCategory;
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

	public function getImage($size = null)
	{
		if (empty($size))
		{
			return $this->_image;
		}
		return str_replace('/obrazy/', "/_{$size}/obrazy/", $this->_image);
	}

	public function getAddress()
	{
		return $this->_address;
	}

	public function setAddress($address)
	{
		$this->_address = $address;
		return $this;
	}

	public function getWww()
	{
		return $this->_www;
	}

	public function setWww($www)
	{
		$this->_www = $www;
		return $this;
	}

	public function getCategory()
	{
		if (is_null($this->__category) || $this->__category->getId() != $this->getIdCategory())
		{
			$mapper = new Cms_Db_Mapper_Category();
			$mapper->find($this->getIdCategory(), $this->__category = new Cms_Model_Category());
		}
		return $this->__category;
	}

	public function getUrl($action = null)
	{
		$baseUrl = new Zend_View_Helper_BaseUrl();
		switch ($action)
		{
			case 'cms-edit':
				return $baseUrl->baseUrl('cms/local/edit/id/' . $this->getId());
			default:
				return $baseUrl->baseUrl('local/' . $this->getId());
		}
	}

}