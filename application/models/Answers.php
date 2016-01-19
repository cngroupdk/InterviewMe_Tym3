<?php

/**
 * List of all answers
 *
 */
class Answers extends My_Db_Table {

    /**
     * DB table name
     *
     * @var string
     */
    protected $_name = 'answer';

    /**
     * Name of the class that represents a single record
     *
     * @var string
     */
    protected $_rowClass = 'Answer';

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
        )
    );

}