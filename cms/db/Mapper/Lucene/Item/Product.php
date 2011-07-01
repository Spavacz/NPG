<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author Spav
 */
class Cms_Db_Mapper_Lucene_Item_Product
{
	protected $_index;

	public function  __construct()
	{
		$this->_index = Zend_Search_Lucene::open( APPLICATION_PATH . '/../data/index-products' );
	}

	public function find( $query, $field = null)
	{
		if( !is_null($field) )
		{
			$query = $field . ":\"{$query}\"";
			
		}
		return $this->_index->find($query);
	}

	public function save( Cms_Model_Item_Product $product )
	{
		$doc = new Zend_Search_Lucene_Document();
		$doc->addField( Zend_Search_Lucene_Field::Keyword('id_product', $product->getId()) ); // id
		$doc->addField( Zend_Search_Lucene_Field::text('name', $product->getName()) ); // nr
		$doc->addField( Zend_Search_Lucene_Field::Keyword('price', $product->getPrice()) ); // cena
		$doc->addField( Zend_Search_Lucene_Field::Keyword('rooms', $product->getRooms()) ); // ilosc pokoi
		$doc->addField( Zend_Search_Lucene_Field::Keyword('type', $product->getType()) ); // typ
		$doc->addField( Zend_Search_Lucene_Field::Keyword('id_category', $product->getIdCategory()) ); // kategoria
		// @todo inne parametry
		$this->_index->addDocument($doc);
	}

	public function delete( Cms_Model_Item_Product $product )
	{
		// usuwamy ewentualny stary wips z lucyny
		$query = 'id_product:' . $product->getId();
		$hits  = $this->_index->find($query);
		foreach ($hits as $hit) {
			$this->_index->delete($hit->id);
		}
	}

}
