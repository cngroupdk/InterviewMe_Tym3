<?php

/**
 * Trida reprezentujici  test
 *
 */
class Test extends My_Db_Table_Row {
	
	public function getPosition() {
		return $this->findParentRow('Positions');
	}
	
	public function getTechnology() {
		return $this->findParentRow('Technologies');
	}
	
	public function getSeniority() {
		return $this->findParentRow('Seniorities');
	}
	
    public function getSessionTests() {
        return $this->findDependentRowset('SessionTests');
    }

	public function getTestFor() {
		$string = $this->getSeniority();
		$string .= ' ' . $this->getTechnology();
		$string .= ' ' . $this->getPosition();
		return $string;
	}
	
	/**
	 * All questions.
	 */
    public function getQuestions() {
        return $this->findDependentRowset('Questions');
    }
    
    /**
     * All current questions.
     */
    public function getCurrentQuestions() {
    	$questions = My_Model::get('Questions')->fetchAll(array(
    			'test_id  = ?' => $this->getId(), 'deleted_at IS NULL'
    	));
    	return $questions;
    }
    
    public function updateFromArray(array $values, $isCreated) {
    	$date = date("Y-m-d H:i:s");
    	if ($isCreated) {
    		$this->created_at = $date;
    	} else {
    		$this->last_modified_at = $date;
    	}
    	$this->setFromArray($values);
    	$this->save();
    }
    
    public function toArray() {
    	$values = parent::toArray();
    	return $values;
    }
    
    public function deleteTest() {
    	$this->deleted_at = date("Y-m-d H:i:s");
    	$this->save();
    }
	
}