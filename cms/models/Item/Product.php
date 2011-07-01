<?php

/**
 * 
 * Klasa reprezentujaca Item
 * @author spav
 * 
 */
class Cms_Model_Item_Product extends Cms_Model_Item_Abstract
{
	const TYPE_SELL = 1;
	const TYPE_RENT = 2;

	public static $subtypes = array(
		'0' => 'Wszystkie',
		'01' => 'Mieszkanie',
		'02' => 'Domy (wszystkie)',
		'021' => 'Dom wolnostojący',
		'022' => 'Dom rekreacyjny',
		'023' => 'Dom bliźniak',
		'024' => 'Dom szeregowy',
		'025' => 'Kamienica',
		'03' => 'Działki (wszystkie)',
		'031' => 'Działka rolna',
		'032' => 'Działka handlowa',
		'033' => 'Działka rekreacyjna',
		'034' => 'Działka przemysłowa',
		'035' => 'Działka rzemieślnicza',
		'036' => 'Działka siedliskowa',
		'037' => 'Działka usługowa',
		'038' => 'Działka pod budowę',
		'04' => 'Biuro',
		'05' => 'Sklep',
		'06' => 'Magazyn'
	);
	protected $_address;
	protected $_price;
	protected $_rooms;
	protected $_type;
	protected $_subtype;
	protected $_idCategory;
	protected $_idRootCategory;
	protected $_superGallery;
	protected $_sliderPriority;
	protected $_public;
	protected $__images;
	protected $__mainImage;
	protected $__category;

	public function setAddress($address)
	{
		$this->_address = $address;
		return $this;
	}

	public function getAddress()
	{
		return $this->_address;
	}

	public function setType($type)
	{
		if ($type == self::TYPE_SELL)
		{
			$this->_type = self::TYPE_SELL;
		}
		else
		{
			$this->_type = self::TYPE_RENT;
		}
		return $this;
	}

	/**
	 * 1 - sprzedaz 2 wynajem
	 */
	public function getType()
	{
		return $this->_type;
	}

	public function setPrice($_price)
	{
		$this->_price = (float)$_price;
		return $this;
	}

	public function setSubtype($subtype)
	{
		if (key_exists($subtype, self::$subtypes))
		{
			$this->_subtype = $subtype;
		}
		return $this;
	}

	public function getSubtype($code = true)
	{
		if ($code)
		{
			return $this->_subtype;
		}
		return self::$subtypes[$this->_subtype];
	}

	public function getPrice()
	{
		return $this->_price;
	}

	public function getRooms()
	{
		return $this->_rooms;
	}

	public function setRooms($_rooms)
	{
		$this->_rooms = $_rooms;
		return $this;
	}

	public function getIdCategory()
	{
		return $this->_idCategory;
	}

	public function setIdCategory($_idCategory)
	{
		$this->_idCategory = $_idCategory;
		return $this;
	}

	public function getIdRootCategory()
	{
		return $this->_idRootCategory;
	}

	public function setIdRootCategory($_idRootCategory)
	{
		$this->_idRootCategory = $_idRootCategory;
		return $this;
	}

	/**
	 * Galeria pelnoekranowa
	 * @return <type>
	 */
	public function getSuperGallery()
	{
		return $this->_superGallery;
	}

	public function setSuperGallery($_superGallery)
	{
		$this->_superGallery = $_superGallery;
		return $this;
	}

	public function getSliderPriority()
	{
		return $this->_sliderPriority;
	}

	public function setSliderPriority($_sliderPriority)
	{
		if (ctype_digit($_sliderPriority))
		{
			$this->_sliderPriority = $_sliderPriority;
		}
		else
		{
			$this->_sliderPriority = null;
		}
		return $this;
	}

	public function getPublic()
	{
		return $this->_public;
	}

	public function setPublic($_public)
	{
		$this->_public = $_public;
		return $this;
	}

	public function getCategory()
	{
		if (!$this->__category instanceof Cms_Model_Category)
		{
			$this->__category = Cms_Db_Mapper_Category::factory($this->getIdCategory());
		}
		return $this->__category;
	}

	public function getUrl($action = null)
	{
		$baseUrl = new Zend_View_Helper_BaseUrl();
		switch ($action)
		{
			case 'cms-edit':
				return $baseUrl->baseUrl('cms/products/edit/id/' . $this->getId());
			default:
				return $baseUrl->baseUrl('oferta-' . ($this->getType() == self::TYPE_SELL ? 'sprzedazy'
							: 'wynajmu') . '/' . urlencode($this->getName()));
		}
	}

	public function setImages($images)
	{
		$this->__images = $images;
		return $this;
	}

	/**
	 * Pobiera sciezke do ilustracji produktu
	 *
	 * @param string $thumb rozmiar miniatury
	 * @return string
	 */
	public function getMainImage($thumb = null)
	{
		if (is_null($this->__mainImage))
		{
			$mapper = new Cms_Db_Table_Images();
			$images = $mapper->fetchAll(array('idParent = ?' => $this->getId()), null, 1)->toArray();
			$this->__mainImage = $images[0]['filename'];
		}
		if ($thumb)
		{
			return str_replace('/obrazy/', "/{$thumb}/obrazy/", $this->__mainImage);
		}
		return $this->__mainImage;
	}

	public function getImages($thumb = null)
	{
		if (is_null($this->__images))
		{
			$this->__images = array();
			$mapper = new Cms_Db_Table_Images();
			$images = $mapper->fetchAll(array('idParent = ?' => $this->getId()))->toArray();
			foreach ($images as $image)
			{
				$this->__images[] = $image['filename'];
			}
			if (isset($this->__images[0]))
			{
				$this->__mainImage = $this->__images[0];
			}
		}

		if ($thumb)
		{
			$thumbs = array();
			foreach ($this->__images as $image)
			{
				$thumbs[] = str_replace('/obrazy/', "/{$thumb}/obrazy/", $image);
			}
			return $thumbs;
		}

		return $this->__images;
	}

}