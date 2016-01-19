<?php

class TestController extends Zend_Controller_Action {

    /**
     * A list of all tests
     *
     */
    public function indexAction() {
        $tsts = My_Model::get('Tests')->fetchAll(
        	My_Model::get('Tests')->select()
        	-> where('deleted_at IS NULL')
        	// -> order(); 
        );
        $this->view->tests = $tsts;
        $this->view->title = 'List of Tests';
    }
    
    public function detailAction() {
    	$id = $this->_getParam('id');
    	$test = My_Model::get('Tests')->getById($id);
    	if ($test) {
    		$this->view->test = $test;
                $this->view->title= $test->getName();
    	}
    	else {
    		$this->_helper->redirector->gotoUrl('/test/');
    		// gotoUrl('/my-controller/my-action/param1/test/param2/test2');
    	}
    }
    
    public function editAction() {
    	$record = null;
    	$id = $this->_request->getParam('id');
    	$this->view->id = $id;
    	if (!empty($id)) {
    		$record = My_Model::get('Tests')->getById($id);
    	}
    	$form = new TestForm();
    	$form->setAction($this->_helper->url->url());
    
    	if ($record === null) {
    		$this->view->title = 'Add Test';
    	} else {
    		$this->view->title = 'Edit Test: ' . $record->getName() . ' [' . $record->getId() . ']';
    		$form->setModifyMode();
    	}
    
    	if ($this->_request->isPost()) {
    		if ($form->isValid($this->_request->getPost())) {
    			$formValues = $form->getValues();
    			if ($record === null) {
    				$record = My_Model::get('Tests')->createRow();
    				$record->updateFromArray($formValues, true);
    			} else {
    				$record->updateFromArray($formValues, false);
    			}
    			$this->_helper->flashMessenger->setNamespace("success")->addMessage("Your changes have been saved!");
    			
    			$this->_helper->redirector->gotoUrl('/test/');
    		}
    	} else {
    		if ($record !== null) {
    			$form->populate($record->toArray());
    		}
    	}
    	$this->view->form = $form;
    }
    
    public function deleteAction() {
    	// only POST request
    	if ($this->_request->isPost()) {
    		$id = $this->_getParam('id');
    		if (!empty($id)) {
    			$test = My_Model::get('Tests')->getById($id);
    			if ($test) {
    				$test->deleteTest();
    				$this->_helper->flashMessenger->setNamespace("success")->addMessage("Test was deleted!");
    			}
    		}
    		$this->_helper->redirector->gotoUrl('/test/');
    	}
    }
    
    

}