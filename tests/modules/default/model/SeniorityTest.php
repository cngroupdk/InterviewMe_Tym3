<?php

require_once dirname(__FILE__) . '/../../../TestConfiguration.php';

class SeniorityTest extends My_Test_TestCase {
	
	public function testGetApplicants() {
		$seniority = My_Model::get('Seniority')->getById(3);
		$applicants = $seniority->getApplicants();
		
		$this->asserEquals('1', $applicants[0]->getId());
		
	}
	
}
