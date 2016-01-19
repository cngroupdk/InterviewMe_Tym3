<?php

/**
 * List of all applicants
 *
 */
class Applicants extends My_Db_Table {

    /**
     * DB table name
     *
     * @var string
     */
    protected $_name = 'applicant';

    /**
     * Name of the class that represents a single record
     *
     * @var string
     */
    protected $_rowClass = 'Applicant';

    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array(
        'Position' => array(
            'columns' => array('position_id'),
            'refTableClass' => 'Positions',
            'refColumns' => array('id')
        ),
        'InterviewedBy' => array(
            'columns' => array('interviewed_by'),
            'refTableClass' => 'Users',
            'refColumns' => array('id')
        ),
        'InterviewedBy2' => array(
            'columns' => array('interviewed_by2'),
            'refTableClass' => 'Users',
            'refColumns' => array('id')
        ),
        'InterviewedBy3' => array(
            'columns' => array('interviewed_by3'),
            'refTableClass' => 'Users',
            'refColumns' => array('id')
        ),
        'Technology' => array(
            'columns' => array('technology_id'),
            'refTableClass' => 'Technologies',
            'refColumns' => array('id')
        ),
        'Seniority' => array(
            'columns' => array('seniority_id'),
            'refTableClass' => 'Seniorities',
            'refColumns' => array('id')
        ),
        'ContractForm' => array(
            'columns' => array('contract_form_id'),
            'refTableClass' => 'ContractForms',
            'refColumns' => array('id')
        ),
    );
    public function getApplicansByResult()
    {
        $checkquery = $this  ->select()
                            ->from($this, array("count"=>"COUNT(*)","result"=>"result"))
                ->group("result");
        return  $this->fetchAll($checkquery);
    }
    public function getApplicansByTechnology()
    {
        $checkquery = $this  ->select()
                            ->from($this, array("count"=>"COUNT(*)","technology_id"=>"technology_id"))
                ->group("technology_id")
                ->order("count DESC");
        return  $this->fetchAll($checkquery);
    }
    public function getCountAllApplicants()
    {
        $checkquery = $this  ->select()
                            ->from($this, array("num"=>"COUNT(*)"));;

        $checkrequest = $this->fetchRow($checkquery);
        return( $checkrequest["num"]);
    }
    public function getCountHiredApplicants()
    {
        $checkquery = $this  ->select()
                            ->from($this, array("num"=>"COUNT(*)"))
                            ->where("result = ?", "hired");

        $checkrequest = $this->fetchRow($checkquery);
        return( $checkrequest["num"]);
    }
    public function getCountRejectedApplicants()
    {
        $checkquery = $this  ->select()
                            ->from($this, array("num"=>"COUNT(*)"))
                            ->where("result = ?", "rejected");

        $checkrequest = $this->fetchRow($checkquery);
        return( $checkrequest["num"]);
    }
}