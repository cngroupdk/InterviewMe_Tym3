<?php

/**
 * Position comtroller
 *
 */
class PositionController extends Zend_Controller_Action {

    /**
     * A list of all positions
     *
     */
    public function indexAction()
    {
		$this->view->title = 'List of Positions';
    }
}
