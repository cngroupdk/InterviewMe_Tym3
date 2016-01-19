<?php

class PublictestController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('test');
        /* Initialize action controller here */
        
    //    $this->adminEdit = false;
    }

    public function indexAction()
    {
        $testSessionHash = $this->_getParam('id');
        $sessionTest = My_Model::get('SessionTests')->fetchRow(array('hash  = ?' => $testSessionHash));
        if(!$sessionTest){
            throw new Zend_Controller_Action_Exception('The test was not found', 404);
        }
        if($sessionTest->getStatus() != "Assigned"&&$sessionTest->getStatus() != "Started"){
            $this->render('save');
            return;
            //throw new Zend_Controller_Action_Exception('The test was already finished', 404);
        }
        $this->view->sessionTest = $sessionTest;
        // action body
    }
    
    public function editAction() {
    	$testSessionHash = $this->_getParam('id');
    	$sessionTest = My_Model::get('SessionTests')->fetchRow(array('hash  = ?' => $testSessionHash));
    	if(!$sessionTest){
    		throw new Zend_Controller_Action_Exception('The test was not found', 404);
    	}

    	$this->view->sessionTest = $sessionTest;

    }
    
    public function saveAction()
    {
        $isAdmin = Zend_Registry::get('ACL')->isAllowed(Zend_Registry::get('ROLE'), "applicant","detail");//TODO
        $testSessionHash = $this->_getParam('id');
        $sessionTest = My_Model::get('SessionTests')->fetchRow(array('hash  = ?' => $testSessionHash));
        if(!$sessionTest){
            throw new Zend_Controller_Action_Exception('The test was not found', 404);
        }
        
        //TODO: add condition allowing editing by admin 
        if($sessionTest->getStatus() != "Assigned"&&$sessionTest->getStatus() != "Started"){
      //       throw new Zend_Controller_Action_Exception('The test was already finished', 404);
        }
        if($sessionTest->getStatus()=="Assigned"){
            $sessionTest->updateFromArray(array(
                "status"=>"Started"
            ),false);
        }
        $isTempSave = $this->_getParam('temp');
        if(!$isTempSave&&!$isAdmin){
            $sessionTest->updateFromArray(array(
                "status"=>"Submitted"
            ),false);
        }
        $requestParams = $this->getRequest()->getParams();
        
        $correct = 0;
        $incorrect = 0;
        foreach($sessionTest->getSessionQuestions() as $sessionQuestion){
            $sessionQuestionUpdateArray = array();
            
            switch($sessionQuestion->getQuestion()->getType()){
                case "MultipleChoice":
                case "CodeMultipleChoice":
                    $questionAnswers = $this->_request->getParam("question".$sessionQuestion->getId());

                    if(is_array($questionAnswers)){
                        foreach ($questionAnswers as $selectedAnswer){
                            $found = false;
                            foreach ($sessionQuestion->getSessionAnswers() as $existingSessionAnswer){
                                if($existingSessionAnswer->getAnswer()->getId() == $selectedAnswer){
                                    $found = true;
                                    break;
                                }
                            }
                            if(!$found){
                                $createdSessionAnswer = My_Model::get('SessionAnswers')->createRow();
                                $createdSessionAnswer->updateFromArray(array(
                                    'session_question_id' => $sessionQuestion->getId(),
                                    'answer_id' => $selectedAnswer
                                ),true);
                            }
                        }
                        foreach ($sessionQuestion->getSessionAnswers() as $existingSessionAnswer) {
                            $found = false;
                            foreach ($questionAnswers as $selectedAnswer) {
                                if($selectedAnswer == $existingSessionAnswer->getAnswer()->getId()){
                                    $found = true;
                                }
                            }
                            if(!$found){
                                $existingSessionAnswer->delete();
                            }
                        }      
                    }         
                    break;
                case "Text":
                case "CodeText":
                    $answerText = trim($this->_request->getParam("question".$sessionQuestion->getId()));
                    $sessionQuestionUpdateArray = array_merge($sessionQuestionUpdateArray,array(
                        "answer"=>trim($answerText)
                    ));
                    if($isAdmin&&isset($requestParams["correct".$sessionQuestion->getId()])){
                        $sessionQuestionUpdateArray = array_merge($sessionQuestionUpdateArray,array("score"=>
                            $requestParams["correct".$sessionQuestion->getId()]=="1"?1:0
                        ));
                        if($sessionQuestionUpdateArray["score"]==1){
                            $correct++;
                        }else{
                            $incorrect++;
                        }
                    }
                    break;       
            }
            if($isAdmin&&isset($requestParams["note".$sessionQuestion->getId()])){
                $sessionQuestionUpdateArray = array_merge($sessionQuestionUpdateArray,
                    array(
                        "note"=>trim($requestParams["note".$sessionQuestion->getId()])
                        )
                    );
            }
            $sessionQuestion->updateFromArray($sessionQuestionUpdateArray,true);
        }
        if($isTempSave){
            $this->_helper->viewRenderer->setNoRender(true);
        }else{
            foreach($sessionTest->getSessionQuestions() as $sessionQuestion){
               $question = My_Model::get('Questions')->getById($sessionQuestion->getQuestion()->getId());
               switch($question->getType()){
                   case "MultipleChoice":
                   case "CodeMultipleChoice":
                       $correctAnswers = 0;
                       $incorrectAnswers = 0;
                       
                       foreach ($question->getAnswers() as $answer){
                           $found = false;
                           foreach ($sessionQuestion->getSessionAnswers() as $sessionAnswer){
                           	
                               if($sessionAnswer->getAnswer()->getId() == $answer->getId()){
                                   $found = true;
                               }
                               
                           }
                           if(($answer->getIsCorrect()&&!$found)
                                   || (!$answer->getIsCorrect()&&$found)){
                               $incorrectAnswers++;
                           }else{
                               $correctAnswers++;
                           }
                       }
                       
                       if($incorrectAnswers == 0){
                           $correct++;
                       }else{
                           $incorrect++;
                       }
                       $sessionQuestion->updateFromArray(
                               array( "score"=>$incorrectAnswers == 0?1:0)
                       ,true);
                   break;
               }
            }

            $sessionTest->updateFromArray(array(
                "score"=>($correct/(($correct+$incorrect)==0?1:($correct+$incorrect)))
            ),true);
            if($isAdmin){
                $sessionTest->updateFromArray(array(
                    "status"=>"Reviewed"
                ),false);
            }
           if($isAdmin){
                $this->_helper->redirector("detail","applicant",null,array('id' => $sessionTest->getApplicantId()));
           }
        }
    }
}

