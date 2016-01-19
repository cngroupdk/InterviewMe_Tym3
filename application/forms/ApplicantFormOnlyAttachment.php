<?php

/**
 * Applicant form
 *
 */
class ApplicantFormOnlyAttachment extends Bootstrap_Form {

    /**
     * Form init
     *
     */
    public function init() {
        $this->setMethod('post');
        
        $fileUploader = new Zend_Form_Element_File('photo');
        $fileUploader->setLabel('Photo (max 2MB, JPEG)')
        ->setAttrib('class', 'input')
        ->setDestination(Zend_Registry::get('uploadPath'));
        $fileUploader->addValidator('Count', false, 1);
        $fileUploader->addValidator('Size', false, 2048000);
        $fileUploader->addValidator('Extension', false, 'jpg,jpeg');
        $this->addElement($fileUploader, 'photo');
   
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