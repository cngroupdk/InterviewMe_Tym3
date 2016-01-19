<?php

/**
 * A single file record
 * Because of PHP File class is need diff name.
 *
 */
class File2 extends My_Db_Table_Row {
	
    public function updateFromArray(array $values) {
    	$this->setFromArray($values);
    	$this->save();
    }
    
}