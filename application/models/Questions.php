<?php

/**
 * A list of all questions
 *
 */
class Questions extends My_Db_Table  {

    /**
     * DB table name
     *
     * @var string
     */
    protected $_name = 'question';
    
    /**
     * Name of the class that represents a single record
     *
     * @var string
     */
    protected $_rowClass = 'Question';	
    
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
    	)
    );


}
	