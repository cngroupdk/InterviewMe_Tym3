<?php

require_once dirname(__FILE__) . '/../../../TestConfiguration.php';

class TechnologyTest extends My_Test_TestCase {
	
	public function testGetApplicants() {
		$technology = My_Model::get('Technologies')->getById(4);
		$applicants = $technology->getApplicants();
		
		$this->asserEquals('1', $applicants[0]->getId());
		
	}
	
}
