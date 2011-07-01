<?php

class Cms_Model_Item_Image extends Cms_Model_Item_Abstract
{
	
	protected $_filename;
	
	public function setFilename($filename) 
	{
		$this->_filename = $filename;
		return $this;
	}
	public function getFilename() 
	{
		return $this->_filename;
	}
	
}