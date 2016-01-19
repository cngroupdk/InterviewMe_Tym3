<?php

/**
 * A single seniority record
 *
 */
class Seniority extends My_Db_Table_Row {

    public function getApplicants() {
        return $this->findDependentRowset('Applicants');
    }

    public function __toString()
    {
        return $this->name;
    }
}
