<?php
/**
 * Lucene Category
 *
 * @author Spav
 */
class Cms_Db_Mapper_Lucene_Category
{
	protected $_index;

	public function  __construct()
	{
		$this->_index = Zend_Search_Lucene::open( APPLICATION_PATH . '/../data/index-categories' );
	}

	public function find( $query, $field = null)
	{
		if( !is_null($field) )
		{
			$query = $field . ":\"{$query}\"";
			
		}
		return $this->_index->find($query);
	}

	public function save( Cms_Model_Category $category )
	{
		$doc = new Zend_Search_Lucene_Document();
		$doc->addField( Zend_Search_Lucene_Field::Keyword('id_category', $category->getId()) ); // id
		$doc->addField( Zend_Search_Lucene_Field::Keyword('id_parent', $category->getIdParent()) ); // id
		$doc->addField( Zend_Search_Lucene_Field::text('name', $category->getName()) ); // nr
		// @todo inne parametry
		$this->_index->addDocument($doc);
	}

	public function delete( Cms_Model_Category $category )
	{
		// usuwamy ewentualny stary wips z lucyny
		$query = 'id_category:' . $category->getId();
		$hits  = $this->_index->find($query);
		foreach ($hits as $hit) {
			$this->_index->delete($hit->id);
		}
	}

}
