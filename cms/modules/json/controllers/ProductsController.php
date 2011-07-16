<?php

/**
 * Json CategoriesController
 *
 * @author Spav
 */
class Json_ProductsController extends Zend_Controller_Action
{

	public function init()
	{
// nie chcemy widokow
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function quicksearchAction()
	{
		$categories = $this->_getParam('c');
		$priceMin = (int)$this->_getParam('pmin');
		$priceMax = $this->_getParam('pmax');
		$roomsMin = (int)$this->_getParam('rmin');
		$roomsMax = $this->_getParam('rmax');
		$type = (int)$this->_getParam('t', Cms_Model_Item_Product::TYPE_SELL);
		$section = (int)$this->_getParam('s', 1);

		// advanced
		$subtypes = $this->_getParam('sub');
		$areaMin = $this->_getParam('amin');
		$areaMax = $this->_getParam('amax');
		$floorMin = $this->_getParam('fmin');
		$floorMax = $this->_getParam('fmax');
		$params = $this->_getParam('p');	
		
		$where = array(
			'type = ' . ($type == Cms_Model_Item_Product::TYPE_SELL ? Cms_Model_Item_Product::TYPE_SELL : Cms_Model_Item_Product::TYPE_RENT),
			'section = ' . $section
		);
		$join = array();
		$group = null;
		$having = null;
		
		// categories
		if (!empty($categories))
		{
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
		
		// Advanced
		// subtypes
		if($subtypes) {
			$where[] = 'subtype IN (' . implode(',', $subtypes) . ')';
		}
		// area
		if($areaMin && $areaMax) {
			$where[] = "area BETWEEN {$areaMin} AND {$areaMax}";
		} elseif($areaMin) {
			$where[] = "area >= {$areaMin}";
		} elseif($areaMax) {
			$where[] = "area <= {$areaMax}";
		}
		// floor
		if($floorMin && $floorMax) {
			$where[] = "floor BETWEEN {$floorMin} AND {$floorMax}";
		} elseif($floorMin) {
			$where[] = "floor >= {$floorMin}";
		} elseif($floorMax) {
			$where[] = "floor <= {$floorMax}";
		}
		// params
		if($params) {
			$join[] = array(
				'table' => 'products_parameters',
				'cond' => 'idProduct = id',
				'cols' => array()
			);
			$where[] = 'idParameter IN (' . implode(',', $params) . ')';
			$group = 'idProduct';
			$having = 'COUNT(idProduct) = ' . count($params);
		}

		$where = implode(' AND ', $where);
		$mapper = new Cms_Db_Mapper_Item_Product();
		if($params) {
			$result = $mapper->findFast('id', $where, null, 1, $join, $group, $having);
			$result[0] = count($result);		
		} else {
			$result = $mapper->findFast('COUNT(*)', $where, null, 1, $join, $group, $having);
		}

		header('Content-type: application/json');
		echo json_encode($result[0]);
		die();
	}

	public function indexAction()
	{

	}

    /**
     * Wysyla opis produktu
     *
     * @return void
     */
    public function descriptionAction()
    {
        $id = intval($this->_getParam('id'));
        if(empty($id)) {
            die();
        }

        $mapper = new Cms_Db_Mapper_Item_Product();
        $result = $mapper->findFast(array('description','address'), 'id = ' . $id);
        
		header('Content-type: application/json');
		echo json_encode($result[0]);
    }

}
