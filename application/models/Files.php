<?php

/**
 * A list of all files
 *
 */
class Files extends My_Db_Table  {

    /**
     * DB table name
     *
     * @var string
     */
    protected $_name = 'file';
    
    /**
     * Name of the class that represents a single record
     *
     * @var string
     */
    protected $_rowClass = 'File2';	
    
    /**
     * Reference
     *
     * @var array
     */
    protected $_referenceMap = array(
    		'Applicant' => array(
    				'columns' => array('applicant_id'),
    				'refTableClass' => 'Applicants',
    				'refColumns' => array('id')
    		),
    );


}
	