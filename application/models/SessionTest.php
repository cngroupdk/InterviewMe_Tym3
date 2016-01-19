<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SessionTest extends My_Db_Table_Row {

    public function getApplicant(){
        return $this->findParentRow('Applicants');
    }
    public function getTest(){
        return $this->findParentRow('Tests');
    }
    public function getSessionQuestions() {
        return $this->findDependentRowset('SessionQuestions');
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
}

