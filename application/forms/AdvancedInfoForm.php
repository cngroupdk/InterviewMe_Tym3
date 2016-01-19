<?php
class AdvancedInfoForm extends Bootstrap_Form {

	/**
	 * Form init
	 *
	 */
	public function init() {
		$this->setMethod('post');
		 $this->addElement('text', 'interviewed_on', array(
            'label' => 'Date of interview',
            'icon' => 'calendar',
            'placeholder' => 'e.g. 2016-02-03',
            'class' => 'datepicker',
            'required' => false,
        ));
                 
        $dateValidator = new Zend_Validate_Date('YYYY-MM-DD');
        $this->getElement('interviewed_on')->addValidator($dateValidator);
		
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
		
		$this->addElement('textarea', 'motivation', array(
				'label' => 'Motivation to change',
				'placeholder' => 'e.g. Working on great product',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('textarea', 'preferred_work', array(
				'label' => 'Kind of work preferred',
				'placeholder' => 'e.g. Backend programmer',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		

		$this->addElement('textarea', 'ambitions', array(
				'label' => 'Ambitions',
				'placeholder' => 'e.g. John',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'languages', array(
				'label' => 'Languages',
				'placeholder' => 'e.g. English, German',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
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
		
		$this->addElement('textarea', 'pluses_minuses', array(
				'label' => 'Pluses/minuses',
				'placeholder' => 'e.g. +hard working ',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		$this->addElement('textarea', 'team_experience', array(//TODO save
				'label' => 'Team work experience',
				'placeholder' => 'e.g. Team 10+ ppl ',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('textarea', 'domain', array(
				'label' => 'Main domain/areas of work',
				'placeholder' => 'e.g. Backend project',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('textarea', 'knowhow', array(
				'label' => 'Skills and technologies knowhow',
				'placeholder' => 'e.g. SQL, JS',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('textarea', 'other', array(
				'label' => 'Other information',
				'placeholder' => 'e.g. makes great coffee',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		$this->addElement('textarea', 'summary', array(
				'label' => 'Summary of the interview',
				'placeholder' => 'e.g. summarize applicant qualities',
				'class' => 'col-md-7 col-xs-1 form-control input textarea',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		
		$seniorities = array('' => 'Select an ideal position...');
		foreach (My_Model::get('Positions')->fetchAll() as $position) {
			$seniorities[$position->getId()] = $position->getName();
		}
		$this->addElement('text', 'ideal_for', array(
				'label' => 'Ideal for position/project',
				'class' => 'input',
				'required' => false,
				'filters' => array('StringTrim')
		));
		
		
		$this->addElement('text', 'starting_date', array(
            'label' => 'Possible starting date',
            'icon' => 'calendar',
            'placeholder' => 'e.g. 2016-02-04',
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
				'label' => 'Contract form',
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
                $salaryCurrencies = array('czk' => 'CZK','eur' => 'EUR');//TODO
		$this->addElement('select', 'salary_currency', array(
				'label' => 'Currency',
				'class' => 'input',
				'required' => false,
				'multiOptions' => $salaryCurrencies
		));
		$this->addElement('text', 'contact_date', array(//TODO
                    'label' => 'Next contact date',
                    'icon' => 'calendar',
                    'placeholder' => 'e.g. 2016-02-04',
                    'class' => 'datepicker',
                    'required' => false,
                ));
                $dateValidator = new Zend_Validate_Date('YYYY-MM-DD');
                $this->getElement('starting_date')->addValidator($dateValidator);
		
		
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'class' => 'submit',
            'label' => 'Save'
        ));
	}
}