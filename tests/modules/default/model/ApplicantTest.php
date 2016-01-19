<?php
require_once dirname(__FILE__) . '/../../../TestConfiguration.php';

class ApplicantTest extends My_Test_TestCase {

	
	public function testGetPosition() {
		$applicants = My_Model::get('Applicants')->getById(1);
		$position = $applicants->get(0)->getPosition();
		
		$this->assertEquals('2',$position);
		
	}
	
	public function testGetTechnology() {
		$applicants = My_Model::get('Applicants')->getById(1);
		$technology = $applicants->get(0)->getTechnology();
		
		$this->assertEquals('1',$technology);
	}
	
	public function testGetSeniority() {
		$applicants = My_Model::get('Applicants')->getById(1);
		$seniority = $applicants->get(0)->getSeniority();
		
		$this->assertEquals('1',$seniority);
	}
	
	public function testGetFullName() {
		$applicants = My_Model::get('Applicants')->getById(1);
		$fullName = $applicants->get(0)->getFullName();

		$this->assertEquals('Tom Riddle',$fullName);
	}
}