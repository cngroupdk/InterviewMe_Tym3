<?php

/**
 * A single question record
 *
 */
class Question extends My_Db_Table_Row {
	
	public function getTest() {
		return $this->findParentRow('Tests');
	}
	
	/**
	 * All answers for question.
	 */
	public function getAnswers() {
		return $this->findDependentRowset('Answers');
	}
	
    public function updateFromArray(array $values) {
    	$date = date("Y-m-d H:i:s");
        $this->created_at = $date;
    	$this->setFromArray($values);
    	$this->save();
    }
    public function deleteQuestion() {
    	$this->deleted_at = date("Y-m-d H:i:s");
    	$this->save();
    }
	
}
