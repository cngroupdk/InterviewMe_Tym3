<?php

require_once dirname(__FILE__) . '/../../../TestConfiguration.php';

class TechnologyTest extends My_Test_TestCase {

	public function testGetApplicants() {
		$technology = My_Model::get('Technologies')->getById(4);
		$applicants = $technology->getApplicants();

		$this->asserEquals('1', $applicants[0]->getId());

	}


	public function testGetPosition() {
		$position = My_Model::get('Tests')->getById(1)->getPositions()[0];
		
		$this->assertEquals('2', $position);
	
	}

	public function testGetTechnology() {
		$technology = My_Model::get('Tests')->getById(1)->getTechnologies()[0];
		
		$this->assertEquals('4', $technology);
	}

	public function testGetSeniority() {
		$seniority = My_Model::get('Tests')->getById(1)->getSeniorities()[0];
		
		$this->assertEquals('3', $seniority);
	}

	public function testGetTestFor() {
		
		
	}

	public function testGetQuestions() {
		$question = My_Model::get('Tests')->getById(1)->getQuestions()[0];
		
		$this->assertNotNull($question);
	}

	public function testUpdateFromArray(array $values, $isCreated) {
	
	}


	public function testDeleteTest() {

	}
	
}
