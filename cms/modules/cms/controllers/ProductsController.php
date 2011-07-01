<?php

class Cms_ProductsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->_helper->layout()->setLayout('admin');
    }

    public function indexAction()
    {
		$type = $this->_getParam('type','sale');
		if($type == 'sale') {
			$where = 'type = 1';
		} elseif($type == 'rent') {
			$where = 'type = 2';
		}
    	$mapper = new Cms_Db_Mapper_Item_Product();
    	$this->view->pageLimit = 10;
    	$this->view->pageTotal = ceil( $mapper->countAll($where) / $this->view->pageLimit );
		$this->view->type = $type;
    }

    public function addAction()
    {
		$type = $this->_getParam('type', 'sale') == 'sale' ? 1 : 2;
		$this->view->form = new Cms_Form_Product($type);
		// sprzedarz / wynajem
		$this->view->form->populate( array('type' => $type) );
		$this->view->type = $type == 1 ? 'sale' : 'rent';
	}
    
    public function editAction()
    {
    	$mapper = new Cms_Db_Mapper_Item_Product();
    	if( !$mapper->find( $this->_getParam('id'), $product = new Cms_Model_Item_Product() ) )
 		{
 			$this->_forward('error', 'error', 'default');
 		}

    	$this->view->form = new Cms_Form_Product($product->getType());


 		// populate
		$data = $product->getOptions();
		$data['images'] = $product->getImages();
		foreach( $product->getParameters() as $parameter ) {
			$data['parameter'][(string)$parameter->getId()] = $parameter->getValue();
		}
		
		$this->view->form->populate( $data );

		$this->view->id = $this->_getParam('id');
		$this->view->type = $product->getType() == 1 ? 'sale' : 'rent';
    }
    
}

