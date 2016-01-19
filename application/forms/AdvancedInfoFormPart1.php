<?php
class AdvancedInfoFormPart1 extends Bootstrap_Form {

	/**
	 * Form init
	 *
	 */
	public function init() {
		$this->setMethod('post');

                    $this->setDisplayForm(false);
		 $this->addElement('text', 'interviewed_on', array(
            'label' => 'Interviewed on*',
            'icon' => 'calendar',
            'placeholder' => 'e.g. 01. 01. 2016',
            'class' => 'datepicker',
            'required' => false,
        ));
        $dateValidator = new Zend_Validate_Date('YYYY-MM-DD');
        $this->getElement('interviewed_on')->addValidator($dateValidator);
		
	
		$users = array('' => 'Select an interviewer...');
		foreach (My_Model::get('Users')->fetchAll() as $user) {
			$users[$user->getId()] = $user->getFirstName()." ".$user->getLastName();
		}
		$this->addElement('select', 'interviewed_by', array(
				'label' => 'Interviewed by',
				'class' => 'input',
				'required' => false,
				'multiOptions' => $users
		));
                $this->addElement('select', 'interviewed_by2', array(
				'label' => 'Interviewed by',
				'class' => 'input',
				'required' => false,
				'multiOptions' => $users
		));
                $this->addElement('select', 'interviewed_by3', array(
				'label' => 'Interviewed by',
				'class' => 'input',
				'required' => false,
				'multiOptions' => $users
		));
		$this->addElement('text', 'education', array(
				'label' => 'Education',
				'placeholder' => 'e.g. High school',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'motivation', array(
				'label' => 'Motivation',
				'placeholder' => 'e.g. Working on great product',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'preferred_work', array(
				'label' => 'Preferred work',
				'placeholder' => 'e.g. Backend programmer',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		

		$this->addElement('text', 'ambitions', array(
				'label' => 'Ambitions',
				'placeholder' => 'e.g. John',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'languages', array(
				'label' => 'Languages',
				'placeholder' => 'e.g. English, German',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'travelling', array(
				'label' => 'Travelling',
				'placeholder' => 'e.g. USA, Great Britain',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'class' => 'submit',
            'label' => 'Save'
        ));
        
        
$this->removeDecorator('form'); // the bit you are looking for :)
Zend_Debug::dump($this->render());
	}
}