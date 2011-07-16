<?php

class Cms_Db_Mapper_Category extends Cms_Db_Mapper_Abstract
{

	private static $__instances = array();
	protected $_dbTableName = 'Cms_Db_Table_Categories';
	protected $_modelName = 'Cms_Model_Category';

	public static function factory($id)
	{
		if (!isset(self::$__instances[$id]) || !self::$__instances[$id] instanceof Cms_Model_Category)
		{
			$mapper = new self;
			$mapper->find($id, self::$__instances[$id] = new Cms_Model_Category());
		}
		return self::$__instances[$id];
	}

	public function save(Cms_Model_Category $category)
	{
		if (is_null($category->getId()))
		{
			$category->setCreated(date('Y-m-d H:i:s'));
		}
		/*
		  // i na koniec index w lucene
		  $lucene = new Cms_Db_Mapper_Lucene_Category();
		  // usuwamy ewentualny stary wips z lucyny
		  $lucene->delete($category);
		  // zapis do lucynki
		  $lucene->save($category);
		 */
		return parent::save($category);
	}

	public function countChildern($idParent)
	{
		return $this->table()->countChildren($idParent);
	}

}