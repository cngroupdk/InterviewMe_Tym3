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
class SessionAnswers extends My_Db_Table  {

    /**
     * DB table name
     *
     * @var string
     */
    protected $_name = 'session_answer';
    
    /**
     * Name of the class that represents a single record
     *
     * @var string
     */
    protected $_rowClass = 'SessionAnswer';	

    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array(
        'Answer' => array(
            'columns' => array('answer_id'),
            'refTableClass' => 'Answers',
            'refColumns' => array('id')
        ),
        'SessionQuestion' => array(
            'columns' => array('session_question_id'),
            'refTableClass' => 'SessionQuestions',
            'refColumns' => array('id')
        )
    );

}
	