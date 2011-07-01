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
		$type = $this->_getParam('t', 'sale');

		$where = array('type = ' . ($type == 'sale' ? Cms_Model_Item_Product::TYPE_SELL : Cms_Model_Item_Product::TYPE_RENT));
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

		$where = implode(' AND ', $where);
		$mapper = new Cms_Db_Mapper_Item_Product();
		$result = $mapper->findFast('COUNT(*)', $where);

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
