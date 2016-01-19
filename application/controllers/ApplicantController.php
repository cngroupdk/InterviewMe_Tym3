<?php

/**
 * Applicant controller
 *
 */
class ApplicantController extends Zend_Controller_Action {

    /**
     * A list of all applicants
     *
     */
    public function indexAction() {
        $applicants = My_Model::get('Applicants')->fetchAll(
                $where = array('deleted_at IS NULL'));
        $this->view->applicants = $applicants;
        $this->view->title = 'List of Applicants';
    }

    /**
     * A detail of a single applicant
     *
     */
    public function detailAction() {
        $applicantId = $this->_getParam('id');
        $applicant = My_Model::get('Applicants')->getById($applicantId);

        if (!$applicant || $applicant->getDeletedAt() != null) {
            throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
        }
        $this->view->applicant = $applicant;
        $this->view->sessionTests = My_Model::get('SessionTests')->fetchAll(array('applicant_id  = ?' => $applicantId, 'deleted_at IS NULL'));
        $this->view->tests = My_Model::get('Tests')->fetchAll($where = array('deleted_at IS NULL'));
        $this->view->title = $applicant->getFullName();
    }
    
    public function assigntestAction() {
        $user = Zend_Auth::getInstance()->getIdentity();
        $applicantId = $this->_getParam('id');
        $applicant = My_Model::get('Applicants')->getById($applicantId);

        if ($this->_request->isPost()) {
            if (!$applicant || $applicant->getDeletedAt() != null) {
                throw new Zend_Controller_Action_Exception('The applicant was not found', 404);
            }
            $testId = $this->getRequest()->getPost('test');
            $test = My_Model::get('Tests')->getById($testId);
            if (!$test || $test->getDeletedAt() != null) {
                throw new Zend_Controller_Action_Exception('The test was not found', 404);
            }
            
            $sessionTest = My_Model::get('SessionTests')->createRow();
            $data = array(
                'applicant_id' => $applicant->getId(),
                'test_id' => $test->getId(),
                'hash' => uniqid(),
                'status' => "Assigned",//TODO: enum
                'assigned_by' => $user,
            );
            $sessionTest->updateFromArray($data);
            $questions = My_Model::get('Questions')->fetchAll(array('test_id  = ?' => $test->getId(), 'deleted_at IS NULL'));
            foreach ($questions as $question) {
                $sessionQuestion = My_Model::get('SessionQuestions')->createRow();
                $questionData = array(
                'question_id' => $question->getId(),
                'session_test_id' => $sessionTest->getId()
                );
                $sessionQuestion->updateFromArray($questionData);
                
                /*$answers = My_Model::get('Answers')->fetchAll(array('question_id  = ?' => $question->getId()));
                foreach ($answers as $answer) {
                    $sessionAnswer = My_Model::get('SessionAnswers')->createRow();
                    $answerData = array(
                    'answer_id' => $answer->getId(),
                    'session_question_id' => $sessionQuestion->getId()
                    );
                    $sessionAnswer->updateFromArray($answerData);
                }*/
            }
            $this->view->applicant = $applicant;
            $this->view->sessionTests = My_Model::get('SessionTests')->fetchAll(array('applicant_id  = ?' => $applicantId, 'deleted_at IS NULL'));
            $this->view->tests = My_Model::get('Tests')->fetchAll($where = array('deleted_at IS NULL'));
        }
        $this->_helper->redirector("detail", "applicant", 'default', array('id' => $applicantId));
    }

    public function editAction() {
        $record = null;
        $photoFilename = null;

        $applicantId = $this->_request->getParam('id');
        if (!empty($applicantId)) {
            $record = My_Model::get('Applicants')->getById($applicantId);
            if (!$record || $record->getDeletedAt() != null) {
                throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
            }
            $this->view->applicantId = $applicantId;
        }

        //TODO:
        if(Zend_Registry::get('ACL')->isAllowed(Zend_Registry::get('ROLE'), "editApplicantWithoutAccessToData")){
            $form = new ApplicantForm();
        }else{
            $form = new ApplicantFormOnlyAttachment();
        }
        //$form = new ApplicantForm();
        $form->setAction($this->_helper->url->url());


        if ($record === null) {
            $this->view->title = 'Add Applicant';
        } else {
            $this->view->title = 'Edit Applicant';
            $form->setModifyMode();
        }


        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formValues = $form->getValues();
                //XXX: Je to dobytčárna
                if ($form->photo->receive()) {
                    $photo = $form->photo;
                    $oldFullPath = $photo->getFileName();
                    $path_parts = pathinfo($oldFullPath);
                    if ($path_parts) {
                        $photoFilename = $photo->getHash('md5') . '.' . $path_parts['extension'];
                        $newFullPath = $path_parts['dirname'] . '/' . $photoFilename;
                        rename($oldFullPath, $newFullPath);
                    }
                }

                if ($record === null) {
                    $record = My_Model::get('Applicants')->createRow();
                    if ($photoFilename) {
                        $record->setPhotoFilename($photoFilename);
                    }
                    $record->updateFromArray($formValues, true);
                } else {
                    if ($photoFilename) {
                        $record->setPhotoFilename($photoFilename);
                    }
                    $record->updateFromArray($formValues,  false); //do not update created on value
                }
                
                // Prasárna: Uloží se file fo data/upload, to se uloží do BLOBu, a pak maže soubor
                // Musí být zde, po vytvoření řádku se záznamem :) - váže se na něj
                if ($formValues['attach1'] != null || $formValues['attach2'] != null || $formValues['attach3'] != null) {
                	$files = array();
                	if ($formValues['attach1'] != null) {
                		array_push($files, $form->attach1);
                	}
                	if ($formValues['attach2'] != null) {
                		array_push($files, $form->attach2);
                	}
                	if ($formValues['attach3'] != null) {
                		array_push($files, $form->attach3);
                	}
                	//Zend_Debug::dump($files);
                	$record->setFiles($files);
                }
                
                //Zend_Debug::dump($formValues);
                //echo '================================================================<br />';
                //Zend_Debug::dump($formValues);

                $this->_helper->flashMessenger->setNamespace("success")->addMessage("Your changes have been saved!");

                $this->_helper->redirector("detail", "applicant", 'default', array('id' => $record->getId()));
            }
        } else {
            if ($record !== null) {
                $form->populate($record->toArray());
            }
        }

        $this->view->form = $form;
    }

    public function editadvancedinfoAction() {
        $record = null;
        $photoFilename = null;

        $applicantId = $this->_request->getParam('id');
        if (!empty($applicantId)) {
            $record = My_Model::get('Applicants')->getById($applicantId);
            if (!$record || $record->getDeletedAt() != null) {
                throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
            }
            $this->view->applicantId = $applicantId;
        }

        //TODO:
        
        $form = new AdvancedInfoForm();
        
        //$form = new ApplicantForm();
        $form->setAction($this->_helper->url->url());

        $this->view->title = 'Applicant advanced info';

      

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formValues = $form->getValues();
                //XXX: Je to dobytčárna
               foreach($formValues as $key=>$val){
                   if($val == ""){
                       $formValues[$key] = null;
                   }
               }
                $record->updateFromArray($formValues, false);
                $this->_helper->flashMessenger->setNamespace("success")->addMessage("Your changes have been saved!");

                $this->_helper->redirector("detail", "applicant", 'default', array('id' => $applicantId));
            }
        } else {
            if ($record !== null) {
                $form->populate($record->toArray());
            }
        }

        $this->view->form = $form;
    }
    
    public function deleteAction() {

        if ($this->_request->isPost()) {
            $id = $this->_getParam('id');
            if (!empty($id)) {
                $applicant = My_Model::get('Applicants')->getById($id);
                if ($applicant) {
                    $applicant->deleteApplicant();
                    $this->_helper->flashMessenger->addMessage("green:Deleted!:Applicant was deleted.");
                }
            }
            $this->_helper->redirector->gotoRoute(array('controller' => 'applicant'), 'default', true);
        }
    }
    
    /**
     * http://stackoverflow.com/questions/2621438/
     */
    public function downloadfileAction() {
    	$id = $this->_getParam('id');
    	$file = My_Model::get('Files')->getById($id);
    	header("Content-Type: application/octet-stream");
    	header("Content-Disposition: attachment; filename=\"".$file->name."\";");
    	echo $file->file;
    	exit;
    }

    /**
     * Show photo.
     */
    public function photoAction() {
        $applicantId = $this->_getParam('id');
        $applicant = My_Model::get('Applicants')->getById($applicantId);

        $file = Zend_Registry::get('uploadPath') . '/' . $applicant->getPhotoFilename();
        if (is_file($file)) {
            header('Content-Type: image/jpeg');//FIXME: pevně nastaveno na jpeg, i když uploader i cesta by zvládli obecné typy
            readfile($file);

            // disable layout and view
            $this->view->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $file = APPLICATION_PATH.'/../public/images/user.png';
            
            header('Content-Type: image/png');
            readfile($file);

            // disable layout and view
            $this->view->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            
            //throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
        }
    }

    /* public function editQuestionsAction() {
      $questions = My_Model::get('Questions')->fetchAll(array(
      'position_id = ?' =>2
      ));
      var_dump($questions);
      $this->view->applicants = $applicants;
      $this->view->title = 'List of Applicants';
      } */
}
