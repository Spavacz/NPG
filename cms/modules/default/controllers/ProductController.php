<?php

class ProductController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // parameters
        $page = $this->_getParam('strona', 1);
        $type = $this->_getParam('type', 'sale');
        $typeId = $type == 'rent' ? Cms_Model_Item_Product::TYPE_RENT : Cms_Model_Item_Product::TYPE_SELL;

        $categories = $this->_getParam('c', @$_COOKIE[$type . '-search-c']);
        $priceMin = (int)$this->_getParam('pmin', @$_COOKIE[$type . '-search-pmin']);
        $priceMax = $this->_getParam('pmax', @$_COOKIE[$type . '-search-pmax']);
        $roomsMin = (int)$this->_getParam('rmin', @$_COOKIE[$type . '-search-rmin']);
        $roomsMax = $this->_getParam('rmax', @$_COOKIE[$type . '-search-rmax']);
        setcookie($type . '-search-c', $categories);
        setcookie($type . '-search-pmin', $priceMin);
        setcookie($type . '-search-pmax', $priceMax);
        setcookie($type . '-search-rmin', $roomsMin);
        setcookie($type . '-search-rmax', $roomsMax);

        // building query
        $where = array('type = ' . ($type == 'sale' ? Cms_Model_Item_Product::TYPE_SELL : Cms_Model_Item_Product::TYPE_RENT));
		// categories
		if (!empty($categories)) {
			$where[] = 'idCategory IN (' . implode(',', $categories) . ')';
		}
		// price
		if($priceMin && $priceMax) {
			$where[] = "price BETWEEN {$priceMin} AND {$priceMax}";
		} elseif($priceMin) {
			$where[] = "price >= {$priceMin}";
		} elseif($priceMax) {
			$where[] = "price <= {$priceMax}";
		}
		// rooms
		if($roomsMin && $roomsMax) {
			$where[] = "rooms BETWEEN {$roomsMin} AND {$roomsMax}";
		} elseif($roomsMin) {
			$where[] = "rooms >= {$roomsMin}";
		} elseif($roomsMax) {
			$where[] = "rooms <= {$roomsMax}";
		}

        $mapper = new Cms_Db_Mapper_Item_Product();
        //$paginator = Zend_Paginator::factory(48);
        $paginator = Zend_Paginator::factory($mapper->countAll($where));
        $paginator->setItemCountPerPage(4)
                ->setCurrentPageNumber($page);

		$result = $mapper->fetchAll($where, null, $paginator->getItemCountPerPage(), $page);

        $this->view->paginator = $paginator;
        $this->view->type = $type;
        $this->view->products = $result;
    }

    public function recordAction() {
        $name = $this->_getParam('nr');
        $mapper = new Cms_Db_Mapper_Item_Product();

        if(!$mapper->find($mapper->__db()->quoteInto('name = ?', $name),
            $product = new Cms_Model_Item_Product())) {
            die('404');
        }

        $this->view->product = $product;
    }

}

