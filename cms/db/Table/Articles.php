<?php

class Cms_Db_Table_Articles extends Zend_Db_Table_Abstract
{
    protected $_name    = 'articles';
    protected $_primary = 'id';
    protected $_dependentTables = array ('Cms_Db_Table_Item_Parameters');
}