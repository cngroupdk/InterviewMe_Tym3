<?php

/**
 * Applicant comtroller
 *
 */
class TestquestionController extends Zend_Controller_Action {

    public $questionTypes = array(
        array('value' => 'MultipleChoice', 'label' => 'ABCD', 'predefinedAnswers' => true),
        array('value' => 'Text', 'label' => 'Open question', ),
        array('value' => 'CodeMultipleChoice', 'label' => 'Code example with ABCD', 'predefinedAnswers' => true,'hasCode'=>true),
        array('value' => 'CodeText', 'label' => 'Code example with text answer','hasCode'=>true)
        );
    public $languagesTypes = array( 
        array('value' => 'csharp', 'label' => 'C#.Net'),
        array('value' => 'java', 'label' => 'Java'),
        array('value' => 'javascript', 'label' => 'Javascript'),
        array('value' => 'php', 'label' => 'PHP')
        );

    public function indexAction() {
        $testId = $this->_getParam('id');
      
        
        $questions = My_Model::get('Questions')->fetchAll(array('test_id  = ?' => $testId, 'deleted_at IS NULL'));
        $this->view->testId = $this->_getParam('id');
        $this->view->questions = $questions;
        $this->view->test = My_Model::get('Tests')->getById($testId);
        $this->view->title = 'List of questions';
    }

    public function addtestquestionAction() {
        $this->view->testId = $this->_getParam('id');
        $this->view->questionTypes = $this->questionTypes;
        $this->view->languagesTypes = $this->languagesTypes;
        $this->view->test = My_Model::get('Tests')->getById($this->view->testId);
        $this->view->title = 'Editing question';
    }

    public function edittestquestionAction() {
        $this->view->testId = $this->_getParam('test_id');
        $this->view->questionId = $this->_getParam('id');
        $this->view->test = My_Model::get('Tests')->getById($this->view->testId);
        $this->view->questionTypes = $this->questionTypes;
        $this->view->languagesTypes = $this->languagesTypes;
        $question = My_Model::get('Questions')->getById($this->view->questionId);
        $this->view->question = $question;
        $this->view->title = 'Editing question';
        $this->view->answers = array();
        foreach ($question->getAnswers() as $answer) {
            array_push($this->view->answers, array('text' => $answer->getText(), 'is_correct' => $answer->getIsCorrect()));
        }
        $this->renderScript('testquestion/addtestquestion.phtml');
    }

    public function addtestquestionsaveAction() {

        if ($this->_request->isPost()) {
            $selectedQuestionType = null;
            //Check if questionType exists in predefined array
            foreach ($this->questionTypes as $questionType) {
                if ($questionType['value'] == $_POST['question_type']) {
                    $selectedQuestionType = $questionType;
                }
            }
            $selectedLanguage = null;
            //Check if questionType exists in predefined array
            foreach ($this->languagesTypes as $languagesType) {
                if ($languagesType['value'] == $_POST['language']) {
                    $selectedLanguage = $languagesType;
                }
            }
            $nonEmptyAnswers = 0;
            $maxI = -1;
            foreach ($this->getRequest()->getParam('answer_text') as $key => $value) {
                if(strlen($value)>=1){
                    $nonEmptyAnswers++;
                }
                if ($key > $maxI) {
                    $maxI = $key;
                }
            }
            if ($_POST['name']==null || strlen($_POST['name'])<1) {
                throw new Exception("Name cannot be empty");
            }
            if ($_POST['text']==null || strlen($_POST['text'])<1) {
                throw new Exception("Text cannot be empty");
            }
            if ($selectedQuestionType == null) {
                throw new Exception("Cannot save question without type");
            }
            if($selectedQuestionType['hasCode'] == true&&$selectedLanguage == null){
                throw new Exception("You have to select a language");
            }
            if($selectedQuestionType['predefinedAnswers'] == true&&$nonEmptyAnswers<2){
                throw new Exception("You have to specify at least 2 answers with non-empty text");
            }
            $testId = $this->_getParam('id');
            $formValues = array(
                'test_id' => $testId,
                'name' => $_POST['name'],
                'text' => $_POST['text'],
                'code' => $_POST['code'],
                'type' => $selectedQuestionType['value'],
                'language'=>$selectedLanguage['value']
            );
            $question = My_Model::get('Questions')->createRow();
            $question->updateFromArray($formValues);

            $answersSelected = $this->getRequest()->getParam('answer_selected');
            for ($i = 0; $i <= $maxI; $i++) {
                 if(strlen($_POST['answer_text'][$i])<1){
                     continue;
                 }
                $answer = My_Model::get('Answers')->createRow();
                $answerFormArray = array(
                    'question_id' => $question->getId(),
                    'text' => $_POST['answer_text'][$i],
                    'is_correct' => array_key_exists($i, $answersSelected),
                );
                $answer->updateFromArray($answerFormArray);
            }
            
            if($this->_getParam('question_id')){
                $oldQuestion = My_Model::get('Questions')->getById($this->_getParam('question_id'));
                $oldQuestion->deleteQuestion();
            }
            $this->_helper->redirector("index", "testquestion", 'default', array('id' => $testId));
            $this->_helper->flashMessenger->addMessage("Question was added");
        }
    }

    public function deletetestquestionAction() {
        if ($this->_request->isPost()) {
            $questionId = $this->_getParam('id');
            $testId = $this->_getParam('test_id');
            $question = My_Model::get('Questions')->getById($questionId);
            if ($question) {
                $question->deleteQuestion();
                $this->_helper->flashMessenger->addMessage("Question was deleted");
            }
            $this->_helper->redirector("index", "testquestion", 'default', array('id' => $testId));
        }
    }

}
