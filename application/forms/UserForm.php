<?php

/**
 * User form
 *
 */
class UserForm extends Bootstrap_Form {

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

//http://stackoverflow.com/questions/8014011/queyring-database-for-existing-username-with-zend-and-doctrine
        $this->addElement('text', 'username', array(
            'label' => 'Username*',
            'placeholder' => 'e.g. smithj01',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array('min' => 4,
                        'messages' => array(
                            Zend_Validate_StringLength::TOO_SHORT =>
                            'Account username must be at least %min% characters'
                        )
                    )),
                array('Regex', true, array('pattern' => '/^\w(?:[\w\d\.\-_]+)(?:\w|\d)$/',
                        'messages' => array(
                            Zend_Validate_Regex::NOT_MATCH =>
                            "The username contained invalid characters"
                        )
                    )), /*
              array('Db_NoRecordExists', true, array(
              'table' => 'user', 'field' => 'username',
              'messages' => array(
              Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND =>
              "An account with the username '%value%' is already registered"
              )
              )) */
            )
        ));

        $frmPassword1 = new Zend_Form_Element_Password('password', 'password', array(
            'validators' => array(
                array('StringLength', false, array('min' => 6,
                        'messages' => array(
                            Zend_Validate_StringLength::TOO_SHORT =>
                            'Password must be at least %min% characters long.'
                        )
                    )),
            )
        ));
        $frmPassword1->setLabel('Password*')
                ->setAttrib('class', 'col-md-7 col-xs-1 form-control input')
                ->setRequired('true')
                ->addFilter(new Zend_Filter_StringTrim());
        //->addValidator('StringLength', false, array(6, 20))
        $this->addElement($frmPassword1);


        $frmPassword2 = new Zend_Form_Element_Password('confirm_password');
        $frmPassword2->setLabel('Confirm Password*')
                ->setAttrib('class', 'col-md-7 col-xs-1 form-control input')
                ->setRequired('true')
                ->addFilter(new Zend_Filter_StringTrim())
                ->addValidator('StringLength', false, array(6, 20))
                ->addValidator(new Zend_Validate_Identical('password'));
        $this->addElement($frmPassword2);

//        $this->addElement('text', 'password', array(
//            'label' => 'Password (Visible!)*',
//            'class' => 'input',
//            'required' => true,
//            'filters' => array('StringTrim'),
//            'validators' => array(
//                array('validator' => 'StringLength', 'options' => array(6, 20))
//            )
//        ));

        $this->addElement('select', 'role', array(
            'label' => 'Role*',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => array('reviewer' => 'reviewer', 'admin' => 'admin'),
        ));

        $fileUploader = new Zend_Form_Element_File('photo');
        $fileUploader->setLabel('Photo (max 2MB, JPEG)')
                ->setAttrib('class', 'input')
                ->setDestination(Zend_Registry::get('uploadPath'));
        $fileUploader->addValidator('Count', false, 1);
        $fileUploader->addValidator('Size', false, 2048000);
        $fileUploader->addValidator('Extension', false, 'jpg,jpeg');
        $this->addElement($fileUploader, 'photo');

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'class' => 'submit',
            'label' => 'Add User'
        ));
    }

    /**
     * Changes the form to be the edit form
     */
    public function setModifyMode() {
        $this->getElement('submit')->setLabel('Save User');
        $this->removeElement('password');
        $this->removeElement('confirm_password');
    }

}
