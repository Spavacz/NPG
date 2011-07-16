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
        $page = (int)$this->_getParam('strona', 1);
        $type = $this->_getParam('_type', 'sale');
        $typeId = $type == 'rent' ? Cms_Model_Item_Product::TYPE_RENT : Cms_Model_Item_Product::TYPE_SELL;
		$section = (int)$this->_getParam('_section', 1);
        
		$searchNS = new Zend_Session_Namespace('search');
		// jesli post to znaczy ze ustawiamy filtry i przeladowujemy strone
		if($this->getRequest()->isPost()) 
		{
	        $searchNS->filter[$type][$section] = array(
	        	'categories' => $this->_getParam('c'),
	        	'priceMin' => $this->_getParam('pmin'),
	        	'priceMax' => $this->_getParam('pmax'),
	        	'roomsMin' => $this->_getParam('rmin'),
	        	'roomsMax' => $this->_getParam('rmax'),
	        	'subtype' => $this->_getParam('sub'),
	        	'areaMin' => $this->_getParam('amin'),
	        	'areaMax' => $this->_getParam('amax'),
	        	'floorMin' => $this->_getParam('fmin'),
	        	'floorMax' => $this->_getParam('fmax'),
	        	'params' => $this->_getParam('p')
	        );
	        header('Location: ' . $_SERVER['REQUEST_URI']);
	        die();
		} 

		if(isset($searchNS->filter) && isset($searchNS->filter[$type]) && isset($searchNS->filter[$type][$section])) {
			$searchFilters = $searchNS->filter[$type][$section];
		} else {
			$searchFilters = array();
		}

		/*
		 * Advanced search
		 */ 
	    if($this->_getParam('kryteria-wyszukiwania')) {
    		$this->filtersAction($searchFilters, $typeId, $section);
    		return;
    	}
    	
		list($where, $join, $group, $having) = $this->getQueryFilters($searchFilters, $typeId, $section);
		
        $mapper = new Cms_Db_Mapper_Item_Product();
        //$paginator = Zend_Paginator::factory(48);
        
	    $where = implode(' AND ', $where);
	    if($searchFilters['params']) {
			$result = $mapper->findFast('id', $where, null, 1, $join, $group, $having);
			$result[0] = count($result);		
		} else {
			$result = $mapper->findFast('COUNT(*)', $where, null, 1, $join, $group, $having);
		}
        
        $paginator = Zend_Paginator::factory((int)$result[0]);
        $paginator->setItemCountPerPage(10)
                ->setCurrentPageNumber($page);

		$result = $mapper->fetchAll($where, null, $paginator->getItemCountPerPage(), $page, $join, $group, $having);

        $this->view->paginator = $paginator;
        $this->view->type = $type;
        $this->view->section = $section;
        $this->view->sectionName = Cms_Model_Item_Product::$sections[$section];
        $this->view->products = $result;
        $this->view->advancedSearchUrl = strtolower($this->view->sectionName) . '/' 
   			. ($typeId == Cms_Model_Item_Product::TYPE_RENT ? 'wynajem' : 'sprzedaz')
   			. '/oferty/kryteria-wyszukiwania/1';
    }

    public function filtersAction($filters, $typeId, $section) 
    {
    	$type = $typeId == Cms_Model_Item_Product::TYPE_SELL ? 'sale' : 'rent';
    	$this->view->filters = $filters;
    	$this->view->type = $typeId;
    	$this->view->section = $section;
       	$this->view->sectionName = Cms_Model_Item_Product::$sections[$section];
   		$this->view->searchUrl = strtolower($this->view->sectionName) . '/' 
   			. ($typeId == Cms_Model_Item_Product::TYPE_RENT ? 'wynajem' : 'sprzedaz')
   			. '/oferty';

	    // price min/max/step
		$this->view->priceMin = 0;
		if ($typeId == Cms_Model_Item_Product::TYPE_SELL) {
			$this->view->priceMax = 5000000;
			$this->view->priceStep = 50000;
		} else {
			$this->view->priceMax = 50000;
			$this->view->priceStep = 1000;
		}

    	// kategorie glowne
    	$categories = new Cms_Db_Mapper_Category();
		$this->view->categories = $categories->fetchAll('idParent = 0', 'priority ASC');

		// subkategorie (typ zabudowy)
		$this->view->subtypes = Cms_Form_Product::$subtypeOptions[Cms_Model_Item_Product::$sections[$section]];

		$paramsMapper = new Cms_Db_Mapper_Item_Parameter();
		$this->view->params = $paramsMapper->fetchAll(array('type = \'checkbox\' AND (category = 0 OR category = ?)' => $typeId));
		
		// ilosc wynikow
		$mapper = new Cms_Db_Mapper_Item_Product();
		list($where, $join, $group, $having) = $this->getQueryFilters($filters, $typeId, $section);

		$where = implode(' AND ', $where);
	    if($filters['params']) {
			$result = $mapper->findFast('id', $where, null, 1, $join, $group, $having);
			$result[0] = count($result);		
		} else {
			$result = $mapper->findFast('COUNT(*)', $where, null, 1, $join, $group, $having);
		}
		$this->view->totalCount = $result[0];
		
		// ustawiamy wartosci
    	if(isset($filters['categories'])) {
			$filters['categories'] = array_flip($filters['categories']);
		}
		if(isset($filters['subtype'])) {
			$filters['subtype'] = array_flip($filters['subtype']);
		}
   		if(isset($filters['params'])) {
			$filters['params'] = array_flip($filters['params']);
		}
		$this->view->filters = $filters;
		
		// ustawiamy skrypt widoku
    	$this->_helper->viewRenderer('filters');  
    }
    
    public function newbuildingsAction() {
    	// parameters
        $page = $this->_getParam('strona', 1);
        // building query
        $where = array(
        	'newBuilding = 1'
        );

        $mapper = new Cms_Db_Mapper_Item_Product();
        
        // Paginator
        $paginator = Zend_Paginator::factory($mapper->countAll($where));
        $paginator->setItemCountPerPage(10)
        	->setCurrentPageNumber($page);

		$result = $mapper->fetchAll($where, null, $paginator->getItemCountPerPage(), $page);

        $this->view->paginator = $paginator;
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

    public function getQueryFilters($filters, $type, $section) {
    	/*
		 * Search results
		 */ 
		$categories = isset($filters['categories']) ? $filters['categories'] : null;
		$priceMin = isset($filters['priceMin']) ? $filters['priceMin'] : null;
		$priceMax = isset($filters['priceMax']) ? $filters['priceMax'] : null;
		$roomsMin = isset($filters['roomsMin']) ? $filters['roomsMin'] : null;
		$roomsMax = isset($filters['roomsMax']) ? $filters['roomsMax'] : null;
		
		// advanced
		$subtypes = isset($filters['subtype']) ? $filters['subtype'] : null;
		$areaMin = isset($filters['areaMin']) ? $filters['areaMin'] : null;
		$areaMax = isset($filters['areaMax']) ? $filters['areaMax'] : null;
		$floorMin = isset($filters['floorMin']) ? $filters['floorMin'] : null;
		$floorMax = isset($filters['floorMax']) ? $filters['floorMax'] : null;
		$params = isset($filters['params']) ? $filters['params'] : null;

        // building query
        $where = array(
        	'type = ' . $type,
        	'section = ' . $section
        );
        $join = array();
		$group = null;
		$having = null;
		
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
				'cond' => 'products_parameters.idProduct = products.id',
				'cols' => array()
			);
			$where[] = 'idParameter IN (' . implode(',', $params) . ')';
			$group = 'idProduct';
			$having = 'COUNT(idProduct) = ' . count($params);
		}
		
		return array($where, $join, $group, $having);
    }
    
}

