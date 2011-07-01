<?php

class Cms_Model_Page extends Cms_Model //Zend_Navigation_Page_Uri
{

	protected $_id;
	protected $_idParent;
	protected $_label;
	protected $_uri;
	protected $_controller;
	protected $_action;
	protected $_params;
	protected $_layout;
	protected $_visible;
	protected $_order;
	protected $__navigation;
	protected $__isCms = false;
	protected $__blocks;
	protected $__href;

	/* PARAMS FUNCTIONS */

	public function getId()
	{
		return $this->_id;
	}

	public function setId($id)
	{
		$id = (int)$id;
		if (empty($id))
		{
			$id = null;
		}
		$this->_id = $id;
		return $this;
	}

	public function setIdParent($idParent)
	{
		$idParent = (int)$idParent;
		if (empty($idParent))
		{
			$idParent = null;
		}
		$this->_idParent = $idParent;
		return $this;
	}

	public function getIdParent()
	{
		return $this->_idParent;
	}

	public function getLabel()
	{
		return $this->_label;
	}

	public function setLabel($_label)
	{
		$this->_label = $_label;
		return $this;
	}

	public function setUri($uri)
	{
		$this->_uri = (string)$uri;
		return $this;
	}

	public function getUri()
	{
		return $this->_uri;
	}

	/**
	 * Sets controller name
	 *
	 * @param  string|null $controller    controller name
	 * @return Zend_Navigation_Page_Uri   fluent interface, returns self
	 * @throws Zend_Navigation_Exception  if invalid controller name is given
	 */
	public function setController($controller)
	{
		if (null !== $controller && !is_string($controller))
		{
			require_once 'Zend/Navigation/Exception.php';
			throw new Zend_Navigation_Exception(
				'Invalid argument: $controller must be a string or null');
		}

		$this->_controller = $controller;
		return $this;
	}

	/**
	 * Returns controller name
	 *
	 * @return string|null  controller name or null
	 */
	public function getController()
	{
		return $this->_controller;
	}

	/**
	 * Sets action name
	 *
	 * @param  string $action             action name
	 * @return Zend_Navigation_Page_Uri   fluent interface, returns self
	 * @throws Zend_Navigation_Exception  if invalid $action is given
	 */
	public function setAction($action)
	{
		if (null !== $action && !is_string($action))
		{
			require_once 'Zend/Navigation/Exception.php';
			throw new Zend_Navigation_Exception(
				'Invalid argument: $action must be a string or null');
		}

		$this->_action = $action;
		return $this;
	}

	/**
	 * Returns action name
	 *
	 * @return string|null  action name
	 */
	public function getAction()
	{
		return $this->_action;
	}

	public function getParams()
	{
		return $this->_params;
	}

	public function setParams($_params)
	{
		if (is_string($_params))
		{
			$_params = unserialize($_params);
		}
		$this->_params = $_params;
		return $this;
	}

	public function setLayout($layout)
	{
		$this->_layout = $layout;
		return $this;
	}

	public function getLayout()
	{
		return $this->_layout;
	}

	public function getVisible()
	{
		return $this->_visible;
	}

	public function setVisible($_visible)
	{
		$this->_visible = $_visible;
		return $this;
	}

	/**
     * Returns page order used in parent container
     *
     * @return int|null  page order or null
     */
	public function getOrder()
	{
		return $this->_order;
	}

	/**
	 * Sets page order to use in parent container
	 *
	 * @param  int $order                 [optional] page order in container.
	 *                                    Default is null, which sets no
	 *                                    specific order.
	 * @return Zend_Navigation_Page       fluent interface, returns self
	 * @throws Zend_Navigation_Exception  if order is not integer or null
	 */
	public function setOrder($order = null)
	{
		if (is_string($order))
		{
			$temp = (int)$order;
			if ($temp < 0 || $temp > 0 || $order == '0')
			{
				$order = $temp;
			}
		}

		if (null !== $order && !is_int($order))
		{
			require_once 'Zend/Navigation/Exception.php';
			throw new Zend_Navigation_Exception(
				'Invalid argument: $order must be an integer or null, ' .
				'or a string that casts to an integer');
		}

		$this->_order = $order;

		return $this;
	}

	/* HELPER FUNCTIONS */

	public function getNavigation()
	{
		if (is_null($this->__navigation))
		{
			$options = $this->getOptions();
			$options['uri'] = $this->getHref();
			$nav = new Zend_Navigation_Page_Uri($options);
			$this->setNavigation($nav);
		}
		return $this->__navigation;
	}

	public function setNavigation(Zend_Navigation_Page_Uri $navigation)
	{
		$this->__navigation = $navigation;
		return $this;
	}

	public function isCms($bool = null)
	{
		if (is_null($bool))
		{
			return $this->__isCms;
		}
		$this->__isCms = (bool)$bool;
		return $this;
	}

	public function getBlocks()
	{
		if (is_null($this->__blocks))
		{
			$blocks = new Cms_Db_Mapper_Block();
			$this->__blocks = $blocks->fetchAllOnPage($this);
		}
		return $this->__blocks;
	}

	public function getHref()
	{
		if (is_null($this->__href))
		{
			$helper = new Zend_View_Helper_BaseUrl();
			$this->__href = $helper->baseUrl(trim($this->getUri(), '/'));
		}
		return $this->__href;
	}

	/* STATIC FUNCTIONS */

	public static function normalizeUrl($string, $length = 255)
	{
		$file_name = mb_strtolower($string);
		$file_name = strtr($file_name, array('ę' => 'e', 'ó' => 'o', 'ą' => 'a', 'ś' => 's', 'ł' => 'l', 'ż' => 'z', 'ź' => 'z', 'ć' => 'c', 'ń' => 'n'));
		$file_name = ereg_replace("[^a-z^A-Z^0-9^ ^-]", "", $file_name);
		$file_name = preg_replace('/\s+/', " ", $file_name);
		$file_name = substr($file_name, 0, $length);
		$file_name = trim($file_name);
		$file_name = str_replace(" ", "-", $file_name);
		return $file_name;
	}

}
