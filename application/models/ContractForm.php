<?php

/**
 * A single contract form record
 *
 */
class ContractForm extends My_Db_Table_Row {

    public function __toString() {
        return $this->name;
    }

}
