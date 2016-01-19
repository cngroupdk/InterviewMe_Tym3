<?php
class AdvancedInfoForm extends Bootstrap_Form {

	/**
	 * Form init
	 *
	 */
	public function init() {
		$this->setMethod('post');

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
		
		$this->addElement('text', 'pluses_minuses', array(
				'label' => 'Pluses and minuses',
				'placeholder' => 'e.g. +hard working ',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'domain', array(
				'label' => 'Main domain/areas of work',
				'placeholder' => 'e.g. Backend project',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'knowhow', array(
				'label' => 'Knowhow',
				'placeholder' => 'e.g. SQL, JS',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'other', array(
				'label' => 'Other',
				'placeholder' => 'e.g. makes great coffee',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'summary', array(
				'label' => 'Summary of applicant',
				'placeholder' => 'e.g. summarize applicant qualities',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		
		$seniorities = array('' => 'Select an ideal position...');
		foreach (My_Model::get('Positions')->fetchAll() as $position) {
			$seniorities[$position->getId()] = $position->getName();
		}
		$this->addElement('select', 'summary', array(
				'label' => 'Ideal for*',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim'),
				'multiOptions' => $seniorities
		));
		
		
		$this->addElement('text', 'starting_date', array(
            'label' => 'Starting date',
            'icon' => 'calendar',
            'placeholder' => 'e.g. 15. 02. 2016',
            'class' => 'datepicker',
            'required' => false,
        ));
        $dateValidator = new Zend_Validate_Date('YYYY-MM-DD');
        $this->getElement('starting_date')->addValidator($dateValidator);
		
		
                $contractForms = array('' => 'Select an ideal position...');
		foreach (My_Model::get('ContractForms')->fetchAll() as $contractForm) {
			$contractForms[$contractForm->getId()] = $contractForm->getName();
		}
		$this->addElement('select', 'contract_form_id', array(
				'label' => 'Contract form ID',
				'placeholder' => 'e.g. ID of contract form',
				'class' => 'input',
				'required' => false,
				'multiOptions' => $contractForms
		));
		
		$this->addElement('text', 'salary', array(
				'label' => 'Salary',
				'placeholder' => 'e.g. Salary',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		$numberValidator = new Zend_Validate_Digits();
		$this->getElement('salary')->addValidator($numberValidator);
		
		
		$result = array(
                    'invitedtointerview'=>'Invited to interview',
                    'waitingforresponse'=>'Waiting for response',
                    'accepted'=>'Accepted',
                    'rejected' =>'Rejected',
                    'hired'=>'Hired',
                    
                    'offerdeclined'=>'Offer declined',
                    'assigned'=>'Assigned',
                    'submitted'=>'Submitted',
                    'evaluated'=>'Evaluated'
                    );
		$this->addElement('select', 'result', array(
				'label' => 'Application status*',
				'class' => 'input',
				'required' => true,
				'filters' => array('StringTrim'),
				'multiOptions' => $result
		));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'class' => 'submit',
            'label' => 'Save'
        ));
	}
}