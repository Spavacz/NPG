<?php

class Cms_Db_Table_Item_Parameters extends Zend_Db_Table_Abstract
{
    protected $_name    = 'items_parameters';
    protected $_primary = 'id';
    protected $_dependentTables = array ('Cms_Db_Table_Item_Parameters_Options');
}