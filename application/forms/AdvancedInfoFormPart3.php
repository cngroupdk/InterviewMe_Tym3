<?php
class AdvancedInfoFormPart3 extends Bootstrap_Form {

	/**
	 * Form init
	 *
	 */
	public function init() {
		$this->setMethod('post');

		
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
		$this->addElement('select', 'ideal_for', array(
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