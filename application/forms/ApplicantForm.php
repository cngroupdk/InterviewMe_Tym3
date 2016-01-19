<?php

/**
 * Applicant form
 *
 */
class ApplicantForm extends Bootstrap_Form {

    /**
     * Form init
     *
     */
    public function init() {
        $this->setMethod('post');

        $this->addElement('text', 'first_name', array(
            'label' => 'First Name*',
            'placeholder' => 'e.g. John',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim')
        ));

        $this->addElement('text', 'last_name', array(
            'label' => 'Last Name*',
            'placeholder' => 'e.g. Smith',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'birth_date', array(
            'label' => 'Birth Date*',
            'icon' => 'calendar',
            'placeholder' => 'e.g. 1982-10-01',
            'class' => 'datepicker',
            'required' => true,
        ));
        $dateValidator = new Zend_Validate_Date('YYYY-MM-DD');
        $this->getElement('birth_date')->addValidator($dateValidator);

        $seniorities = array('' => 'Select a seniority...');
        foreach (My_Model::get('Seniorities')->fetchAll() as $seniority) {
            $seniorities[$seniority->getId()] = $seniority->getName();
        }
        $this->addElement('select', 'seniority_id', array(
            'label' => 'Seniority*',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => $seniorities
        ));

        $technologies = array('' => 'Select a technology...');
        foreach (My_Model::get('Technologies')->fetchAll() as $technology) {
            $technologies[$technology->getId()] = $technology->getName();
        }
        $this->addElement('select', 'technology_id', array(
            'label' => 'Technology*',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => $technologies
        ));

        $positions = array('' => 'Select a position...');
        foreach (My_Model::get('Positions')->fetchAll() as $position) {
            $positions[$position->getId()] = $position->getName();
        }
        $this->addElement('select', 'position_id', array(
            'label' => 'Position*',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => $positions
        ));

        $fileUploader = new Zend_Form_Element_File('photo');
        $fileUploader->setLabel('Photo (max 2MB, JPEG)')
                ->setAttrib('class', 'input')
                ->setDestination(Zend_Registry::get('uploadPath'));
        $fileUploader->addValidator('Count', false, 1);
        $fileUploader->addValidator('Size', false, 2048000);
        $fileUploader->addValidator('Extension', false, 'jpg,jpeg');
        $this->addElement($fileUploader, 'photo');

        $this->addElement('textarea', 'note', array(
            'label' => 'Note',
            'placeholder' => 'Interesting facts about the applicant...',
            'class' => 'col-md-7 col-xs-1 form-control input textarea',
            'filters' => array('StringTrim')
        ));

        $fileUploader = new Zend_Form_Element_File('attach1');
        $fileUploader->setLabel('Attachment (max 15MB)')
                ->setAttrib('class', 'input')
                ->setDestination(Zend_Registry::get('uploadPath'));
        $fileUploader->addValidator('Count', false, 1);
        $fileUploader->addValidator('Size', false, 15728640);
        $this->addElement($fileUploader, 'attach1');

        $fileUploader = new Zend_Form_Element_File('attach2');
        $fileUploader->setLabel('Attachment (max 15MB)')
                ->setAttrib('class', 'input')
                ->setDestination(Zend_Registry::get('uploadPath'));
        $fileUploader->addValidator('Count', false, 1);
        $fileUploader->addValidator('Size', false, 15728640);
        $this->addElement($fileUploader, 'attach2');

        $fileUploader = new Zend_Form_Element_File('attach3');
        $fileUploader->setLabel('Attachment (max 15MB)')
                ->setAttrib('class', 'input')
                ->setDestination(Zend_Registry::get('uploadPath'));
        $fileUploader->addValidator('Count', false, 1);
        $fileUploader->addValidator('Size', false, 15728640);
        $this->addElement($fileUploader, 'attach3');

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'class' => 'submit',
            'label' => 'Add Applicant'
        ));
    }

    /**
     * Changes the form to be the edit form
     */
    public function setModifyMode() {
        $this->getElement('submit')->setLabel('Save Applicant');
    }

}
