<?php

require_once dirname(__FILE__) . '/../../../TestConfiguration.php';

class PositionTest extends My_Test_TestCase {
	
	public function testGetApplicants() {
		$position = My_Model::get('Position')->getById(2);
		$applicants = $position->getApplicants();
		
		$this->asserEquals('1', $applicants[0]->getId());
		
	}
	
}
