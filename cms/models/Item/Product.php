<?php

/**
 * 
 * Klasa reprezentujaca Item
 * @author spav
 * 
 */
class Cms_Model_Item_Product extends Cms_Model_Item_Abstract
{
	/**
	 * Sprzedaż i Wynajem
	 * @var int
	 */
	const TYPE_SELL = 1;
	const TYPE_RENT = 2;

	/**
	 * Działy serwisu
	 * @var array
	 */
	public static $sections = array(
		1 => 'Domy',
		2 => 'Mieszkania',
		3 => 'Obiekty'
	);
	
	/**
	 * Typy nieruchomości
	 * @var array
	 */
	/*public static $subtypes = array(
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
    );*/
	protected $_idAgent;
	protected $_address;
	protected $_price;
	protected $_rooms;
	protected $_area;
	protected $_floor;
	protected $_section;
	protected $_type;
	protected $_subtype;
	protected $_idCategory;
	protected $_idRootCategory;
	protected $_superGallery;
	protected $_sliderPriority;
	protected $_newBuilding;
	protected $_public;
	protected $__images;
	protected $__mainImage;
	protected $__category;
	protected $__agent;



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
	 * @return the $_section
	 */
	public function getSection() 
	{
		return $this->_section;
	}

	/**
	 * @param int $_section
	 */
	public function setSection($_section) 
	{
		$this->_section = $_section;
		return $this;
	}

	/**
	 * 1 - sprzedaz
	 * 2 - wynajem
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
	
	public function getArea()
	{
		return $this->_area;
	}
	
	public function setArea($_area)
	{
		$this->_area = (int)$_area;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getFloor() {
		return $this->_floor;
	}

	/**
	 * @param int $_floor
	 */
	public function setFloor($_floor) {
		$this->_floor = (int)$_floor;
		return $this;
	}

	public function setSubtype($subtype)
	{
		//if (key_exists($subtype, self::$subtypes))
		//{
			$this->_subtype = $subtype;
		//}
		return $this;
	}

	public function getSubtype($code = true)
	{
		if ($code)
		{
			return $this->_subtype;
		}
		return Cms_Form_Product::$subtypes[$this->_subtype];
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
	
	/**
	 * @return int
	 */
	public function getNewBuilding() {
		return $this->_newBuilding;
	}

	/**
	 * @param int $_newBuilding
	 */
	public function setNewBuilding($_newBuilding) {
		$this->_newBuilding = $_newBuilding;
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
				return $baseUrl->baseUrl(strtolower(self::$sections[$this->getSection()])
					. '/' . ($this->getType() == self::TYPE_SELL ? 'sprzedaz' : 'wynajem') 
					. '/oferta/' . urlencode($this->getName()));
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
			if(empty($images)) {
				$this->__mainImage = '/images/noimg.png';	
			}
			else 
			{
				$this->__mainImage = $images[0]['filename'];
			}
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

	/**
	 * @param int $idAgent
	 * @return Cms_Model_Item_Product
	 */
	public function setIdAgent($idAgent)
	{
		$this->_idAgent = $idAgent;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getIdAgent()
	{
		return $this->_idAgent;
	}

	/**
	 * @return Cms_Model_Users
	 */
	public function getAgent()
	{
		if(is_null($this->__agent))
		{
			$mapper = new Cms_Db_Mapper_User();
			$mapper->find($this->getIdAgent(), $this->__agent = new Cms_Model_User());
		}
		return $this->__agent;
	}

}