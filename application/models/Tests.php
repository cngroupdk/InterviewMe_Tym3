<?php 
/**
 * Trida reprezentujici testy (nevyplněné)
 *
 */
class Tests extends My_Db_Table {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'test';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Test';
    
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
    );
     public function getCount($technologyId)
    {
         $checkquery = $this  ->select()
                            ->from($this, array("num"=>"COUNT(*)"))
                            ->where("technology_id = ?", $technologyId);

                         $checkrequest = $this->fetchRow($checkquery);
                         return( $checkrequest["num"]);
    
    }
     
    
}