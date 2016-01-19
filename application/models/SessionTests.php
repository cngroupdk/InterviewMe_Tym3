<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * A list of all seniorities
 *
 */
class SessionTests extends My_Db_Table  {

    /**
     * DB table name
     *
     * @var string
     */
    protected $_name = 'session_test';
    
    /**
     * Name of the class that represents a single record
     *
     * @var string
     */
    protected $_rowClass = 'SessionTest';	

    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array(
        'Test' => array(
            'columns' => array('test_id'),
            'refTableClass' => 'Tests',
            'refColumns' => array('id')
        ),
        'Applicant' => array(
            'columns' => array('applicant_id'),
            'refTableClass' => 'Applicants',
            'refColumns' => array('id')
        )
    );
    public function getTestsByScore()
    {
         $checkquery = $this->select()
                            ->from(array("s"=>"session_test"), array("count"=>"COUNT(*)","average"=>"AVG(score)"))
                                ->join(array("t"=>"test"),
                                  's.test_id = t.id',
                    array("*"))
                            ->group("s.test_id")
                 ->where("(status = 'Submitted' or status = 'Reviewed')")
                 ->where("s.deleted_at IS NULL")
                 ->where("t.deleted_at IS NULL")
                 
                 ->order(array("average ASC"))
                 ->setIntegrityCheck(false);
        return $this->fetchAll($checkquery);
    
    }
}
	