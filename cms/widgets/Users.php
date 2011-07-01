<?php

/**
 * Product Widgets
 *
 * @author Spav
 */
class Cms_Widget_Users extends Cms_Widget
{

	public function agentsAction()
	{
		$mapper = new Cms_Db_Mapper_User();
        $agents = $mapper->fetchAll('agent = 1', 'RAND()', 3);
		$this->view()->agents = $agents;
	}
}