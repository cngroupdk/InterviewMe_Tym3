<?php
require_once dirname(__FILE__) . '/../../../TestConfiguration.php';

class QuestionTest extends My_Test_TestCase {
	

public function testGetTest() {
	$question = My_Model::get('Questions')->getById(1);
	$testId = $question->getTest()[0];
	
	$this->assertEquals('5', $testId);

}

public function testGetAnswers() {
	$answers = My_Model::get('Questions')->getById(1)->getAnswers();	

	$this->assertNotNull($answers);
}

public function testUpdateFromArray(array $values) {

}

public function testDeleteQuestion() {
	My_Model::get('Questions')->getById(1)->deleteQuestion();
	$question = My_Model::get('Questions')->getById(1);
	
	$this->assertNull($question);
}

}