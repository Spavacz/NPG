<?php

class Cms_Model_User extends Cms_Model
{

	protected $_id;
	protected $_name;
	protected $_surname;
	protected $_email;
	protected $_phone;
	protected $_job;
	protected $_photo;
	protected $_description;
	protected $_agent;
	protected $_created;
	protected $__roles;
	protected $__password;

	public function setId($id)
	{
		$this->_id = is_numeric($id) ? (int)$id : null;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setPassword($password)
	{
		if (!empty($password))
		{
			$this->__password = md5($password);
		}
		else
		{
			$this->__password = null;
		}
		return $this;
	}

	public function getPassword()
	{
		return $this->__password;
	}

	/**
	 * Ustawia liste roli uzytkownika
	 * 
	 * @param array $roles
	 */
	public function setRoles($roles)
	{
		$this->__roles = is_array($roles) ? $roles : null;
		return $this;
	}

	/**
	 * Zwraca liste roli uzytkownika
	 * 
	 * @return array
	 */
	public function getRoles()
	{
		return $this->__roles;
	}

	public function setName($name)
	{
		$this->_name = !empty($name) ? $name : null;
		return $this;
	}

	public function getName($fullname = false)
	{
		if ($fullname)
		{
			return $this->_name . ' ' . $this->getSurname();
		}
		return $this->_name;
	}

	public function setSurname($surname)
	{
		$this->_surname = $surname;
		return $this;
	}

	public function getSurname()
	{
		return $this->_surname;
	}

	public function setEmail($email)
	{
		$validator = new Zend_Validate_EmailAddress();
		$this->_email = $validator->isValid($email) ? $email : null;
		return $this;
	}

	public function getEmail()
	{
		return $this->_email;
	}

	public function setPhone($phone)
	{
		$this->_phone = !empty($phone) ? $phone : null;
		return $this;
	}

	public function getPhone()
	{
		return $this->_phone;
	}

	public function setJob($job)
	{
		$this->_job = !empty($job) ? $job : null;
		return $this;
	}

	public function getJob()
	{
		return $this->_job;
	}

	public function setPhoto($photo)
	{
		$this->_photo = !empty($photo) ? $photo : null;
		return $this;
	}

	public function getPhoto($size = '')
	{
		if (empty($size))
		{
			return $this->_photo;
		}
		return str_replace('/obrazy/', "/_{$size}/obrazy/", $this->_photo);
	}

	public function setDescription($description)
	{
		$this->_description = !empty($description) ? $description : null;
		return $this;
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function getNewMessagesNumber()
	{
		return 0;
	}

	public function setAgent($bool)
	{
		$this->_agent = (bool)$bool;
		return $this;
	}

	public function getAgent()
	{
		return $this->_agent;
	}

	public function getCreated()
	{
		return $this->_created;
	}

	public function setCreated($_created)
	{
		$this->_created = $_created;
		return $this;
	}

	public function toArray()
	{
		return array(
			'id' => $this->id,
			'name' => $this->name,
			'surname' => $this->surname,
			'email' => $this->email,
			'agent' => $this->agent,
			'phone' => $this->phone
		);
	}

}