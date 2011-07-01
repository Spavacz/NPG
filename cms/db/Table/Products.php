<?php

class Cms_Db_Table_Products extends Zend_Db_Table_Abstract
{
    protected $_name    = 'products';
    protected $_primary = 'id';
    protected $_dependentTables = array ('Cms_Db_Table_Item_Parameters');
}