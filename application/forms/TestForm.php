<?php

class TestForm extends Bootstrap_Form {

    public function init() {
        $this->setMethod('post');

        $this->addElement('text', 'name', array(
            'label' => 'Name*',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim')
        ));

        $this->addElement('textarea', 'description', array(
            'label' => 'Description',
            'class' => 'col-md-7 col-xs-1 form-control input textarea',
            'required' => false,
            'filters' => array('StringTrim')
        ));

        $seniorities = array('' => 'Select a seniority...');
        foreach (My_Model::get('Seniorities')->fetchAll() as $seniority) {
            $seniorities[$seniority->getId()] = $seniority->getName();
        }
        $this->addElement('select', 'seniority_id', array(
            'label' => 'Seniority*',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => $seniorities,
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
            'multiOptions' => $technologies,
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
            'multiOptions' => $positions,
        ));
        
        $this->addElement('submit', 'submit', array(
        		'ignore' => true,
        		'class' => 'submit',
        		'label' => 'Add Test'
        ));
    }

    /**
     * Changes the form to be the edit form
     */
    public function setModifyMode() {
        $this->getElement('submit')->setLabel('Edit Test');
    }

}
