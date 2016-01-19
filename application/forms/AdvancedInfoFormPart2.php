<?php
class AdvancedInfoFormPart2 extends Bootstrap_Form {

	/**
	 * Form init
	 *
	 */
	public function init() {
		$this->setMethod('post');

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
		
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'class' => 'submit',
            'label' => 'Save'
        ));
	}
}