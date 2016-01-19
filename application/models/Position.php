<?php

/**
 * A single position (test) record
 *
 */
class Position extends My_Db_Table_Row {

    public function getApplicants() {
        return $this->findDependentRowset('Applicants');
    }

    public function __toString() {
        return $this->name;
    }

}
