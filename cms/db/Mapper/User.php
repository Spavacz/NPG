<?php

class Cms_Db_Mapper_User extends Cms_Db_Mapper_Abstract
{

	protected $_dbTableName = 'Cms_Db_Table_Users';
	protected $_modelName = 'Cms_Model_User';

	// @todo zapis roles
	public function save(Cms_Model_User $user)
	{
		$data = $user->getOptions();

		if ($user->getPassword())
		{
			$data['password'] = $user->getPassword();
		}
		if (null === ($id = $user->getId()))
		{
			$data['created'] = date('Y-m-d H:i:s');
			$id = $this->table()->insert($data);
			$user->setId($id);
		}
		else
		{
			$this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}

}
