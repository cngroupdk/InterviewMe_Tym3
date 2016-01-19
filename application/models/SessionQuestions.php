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
class SessionQuestions extends My_Db_Table  {

    /**
     * DB table name
     *
     * @var string
     */
    protected $_name = 'session_question';
    
    /**
     * Name of the class that represents a single record
     *
     * @var string
     */
    protected $_rowClass = 'SessionQuestion';	

    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array(
        'Question' => array(
            'columns' => array('question_id'),
            'refTableClass' => 'Questions',
            'refColumns' => array('id')
        ),
        'SessionTest' => array(
            'columns' => array('session_test_id'),
            'refTableClass' => 'SessionTests',
            'refColumns' => array('id')
        )
    );

}
	