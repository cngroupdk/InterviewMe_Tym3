<?php

/**
 * Formular prihlaseni uzivatele
 *
 */
class LoginForm extends Zend_Form
{
    /**
     * Inicializace formulare
     *
     */
    
    public function init()
    {
        $this->setMethod(self::METHOD_POST);
        
        $user = $this->createElement('text', 'username');
        $user->setAttrib("placeholder", "Username");
        $user->setAttrib("class", "form-control");
        $user->addFilter('StringTrim');
        $user->setRequired(true);
        $this->addElement($user);

        $pass = $this->createElement('password', 'password');
        $pass->setAttrib("placeholder", "Password");
        $pass->setAttrib("class", "form-control");
        $pass->setRequired(true);
        $this->addElement($pass);

        $this->addElement('hidden', 'login', array('value' => 1));

        $button = $this->createElement('submit', 'Log in');
        $button->setAttrib("class", "btn btn-default submit");
        $this->addElement($button);
    }

}