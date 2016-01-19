<?php

/**
 * A single question record
 *
 */
class Answer extends My_Db_Table_Row {
	
    public function updateFromArray(array $values) {
    	
    	$this->setFromArray($values);
    	$this->save();
    }
}