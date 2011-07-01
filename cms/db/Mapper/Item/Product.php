<?php

class Cms_Db_Mapper_Item_Product extends Cms_Db_Mapper_Abstract
{

	protected $_dbTableName = 'Cms_Db_Table_Products';
	protected $_modelName = 'Cms_Model_Item_Product';

	public function save(Cms_Model_Item_Product $product)
	{
		$parametersTable = new Cms_Db_Table_Product_Parameters();
		
		// id root category
		$mapper = new Cms_Db_Mapper_Category();
		$mapper->find($product->getIdCategory(), $category = new Cms_Model_Category());
		$product->setIdRootCategory($category->getIdParent());
		unset($category);
		unset($mapper);

		if (null === ($id = $product->getId()))
		{
			$product->setCreated(date('Y-m-d H:i:s'));
		}
		else
		{
			// czyszcze parametry
			$parametersTable->delete($this->__db()->quoteInto('idProduct = ?', $id)); 
		}

		parent::save($product);

		// parametry
		foreach ($product->getParameters() as $parameter)
		{
			$value = trim($parameter->getValue());
			if (!empty($value))
			{
				$parametersTable->insert(array(
					'idProduct' => $id,
					'idParameter' => $parameter->getId(),
					'value' => $value
				));
			}
		}

		// zdjecia
		$imagesTable = new Cms_Db_Table_Images();
		$old = $imagesTable->fetchAll(array('idParent = ?' => $product->getId()))->toArray();
		$new = $product->getImages();

		// porownujemy zmiany
		foreach ($old as $k => $image)
		{
			if ($stay = array_search($image['filename'], $new))
			{
				unset($old[$k]);
				unset($new[$stay]);
			}
		}
		// usuwamy stare
		foreach ($old as $image)
		{
			$imagesTable->delete('id = ' . $image['id']);
		}
		// zapis nowych
		foreach ($new as $image)
		{
			$imagesTable->insert(array('filename' => $image, 'idParent' => $product->getId()));
		}

		/*
		// i na koniec index w lucene
		$lucene = new Cms_Db_Mapper_Lucene_Item_Product();
		// usuwamy ewentualny stary wips z lucyny
		$lucene->delete($product);
		// zapis do lucynki
		$lucene->save($product);
		*/
		return $this;
	}

	/**
	 * Usuwa Produkt calkowicie z systemu
	 * Uwaga - proces nieodwracalny!
	 *
	 * @param Cms_Model_Item_Product $product
	 */
	public function purge(Cms_Model_Item_Product $product)
	{
		// parametry
		$parametersTable = new Cms_Db_Table_Product_Parameters();
		$where = $this->__db()->quoteInto('idProduct = ?', $product->getId());
		$parametersTable->delete($where);
		// zdjecia
		$imagesTable = new Cms_Db_Table_Images();
		$where = $this->__db()->quoteInto('idParent = ?', $product->getId());
		$parametersTable->delete($where);

		return parent::purge($product);
	}

	public function findFast($cols, $where = null, $limit = null, $page = 1)
	{
		$db = $this->getDbTable()->getAdapter();
		$select = $db->select()
				->from('products', $cols)
				->where('status = 1');
		if ($where)
		{
			$select->where($where);
		}
		if ($limit && $page)
		{
			$select->limitPage($page, $limit);
		}
		if (is_array($cols))
		{
			return $db->fetchAll($select);
		}
		return $db->fetchCol($select);
	}

}