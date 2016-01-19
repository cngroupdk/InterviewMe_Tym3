<?php

/**
 * A single applicant record
 *
 */
class Applicant extends My_Db_Table_Row {

    public function getInterviewers() {
        $interviewer1 = $this->findParentRow('Users','InterviewedBy');
        $interviewer2 = $this->findParentRow('Users','InterviewedBy2');
        $interviewer3 = $this->findParentRow('Users','InterviewedBy3');
        $result = array();
        if($interviewer1 != null){
            $result[] = $interviewer1;
        }
        if($interviewer2 != null){
            $result[] = $interviewer2;
        }
        if($interviewer3 != null){
            $result[] = $interviewer3;
        }
        return $result;
    }
    public function getPosition() {
        return $this->findParentRow('Positions');
    }

    public function getTechnology() {
        return $this->findParentRow('Technologies');
    }

    public function getSeniority() {
        return $this->findParentRow('Seniorities');
    }

    public function getContractForm() {
        return $this->findParentRow('ContractForms');
    }

    public function updateFromArray(array $values, $isCreated) {
        $date = date("Y-m-d H:i:s");
        $this->setFromArray($values);
        if ($isCreated) {
            $this->setCreatedAt($date);
        }
        $this->setLastModifiedAt($date);
        $this->save();

        return $this;
    }

    /**
     * Metoda ukládá soubory (file).
     * 
     * Nelze rozlišit, co je čím nahrazováno.
     * Vše smazáno a nahráno znovu.
     * IDEAL: Do formuláře načíst naše data a vše při každé úpravě nahrávat znovu a znovu.
     * 
     * @param unknown $files
     */
    public function setFiles($files) {
        $service = My_Model::get('Files');

        // smazeme prirazene rubriky
        $service->delete(array('applicant_id = ?' => $this->getId()));

        // nastavime nove
        foreach ($files as $file) {
            $name = basename($file->getFileName());
            $bindata = file_get_contents($file->getFileName());
            unlink($file->getFileName());
            //echo '<br />----------$file --------------------------------<br />';
            //Zend_Debug::dump($file);

            $service->insert(array(
                'applicant_id' => $this->getId(),
                'name' => $name,
                'file' => $bindata,
                'created_at' => date("Y-m-d H:i:s"),
            ));
        }
        return $this;
    }

    public function getFiles() {
        return $this->findDependentRowset('Files');
    }

    public function toArray() {
        $values = parent::toArray();

        return $values;
    }

    public function getAppliesFor() {
        $appliesFor = '';
        $appliesFor = $appliesFor . ' ' . $this->getSeniority();
        $appliesFor = $appliesFor . ' ' . $this->getTechnology();
        $appliesFor = $appliesFor . ' ' . $this->getPosition();

        return $appliesFor;
    }

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function deleteApplicant() {
        $this->deleted_at = date("Y-m-d H:i:s");
        $this->save();
    }

    public function getLatestTestScore() {
        $latestTestScore = '-';

        $table = new SessionTests();
        $tests = $this->findDependentRowset('SessionTests', null, $table->select()->where('(status = "Submitted" or status = "Reviewed")')->order('last_modified_at DESC'));
        
        if ($tests->count() != 0) {
            $latestTestScore = $tests->current()->score;
        }
        
        return $latestTestScore;
    }

    public function _toString() {
        return getFullName();
    }

}
