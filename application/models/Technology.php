<?php

/**
 * A single technology record
 *
 */
class Technology extends My_Db_Table_Row {

    public function getApplicants() {
        return $this->findDependentRowset('Applicants');
    }

    public function __toString() {
        return $this->name;
    }

}
