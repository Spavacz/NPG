<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function agentsAction()
    {
		$mapper = new Cms_Db_Mapper_User();
        $agents = $mapper->fetchAll('agent = 1');
		$this->view->agents = $agents;
    }

}

