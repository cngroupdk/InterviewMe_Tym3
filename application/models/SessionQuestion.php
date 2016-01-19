<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SessionQuestion extends My_Db_Table_Row {

    public function getQuestion(){
        return $this->findParentRow('Questions');
    }
    
    public function getSessionAnswers() {
        return $this->findDependentRowset('SessionAnswers');
    }
    public function updateFromArray(array $values, $isModified) {
    	$this->setFromArray($values);
        $date = date("Y-m-d H:i:s");
        $this->last_modified_at = $date;
    	$this->save();
    }
}

