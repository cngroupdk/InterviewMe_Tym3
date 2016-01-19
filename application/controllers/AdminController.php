<?php

/**
 * Controller pro administraci
 *
 */
class AdminController extends Zend_Controller_Action {

    /**
     * Uvodni konfigurace controlleru
     *
     */
    public function init() {

        //$this->_helper->layout->setLayout('admin');
    }

    /**
     * Uvodni stranka
     *
     */
    public function indexAction() {
        $username = Zend_Auth::getInstance()->getIdentity();
        $this->view->userId = $username;
		//->User_ID;

        $users = My_Model::get('Users');
        $rowset = $users->fetchAll($users->select()->where('username = ?', $username));
        $row = $rowset->current();
        
        $userNamespace = new Zend_Session_Namespace('User');
        $userNamespace->user = $row;
        
        $this->_helper->redirector("index","index");
    }

    /**
     * Odhlaseni uzivatele
     */
    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->flashMessenger->setNamespace("success")->addMessage("Success! You are logged out!");
        $this->_helper->redirector->gotoSimpleAndExit('login', 'admin');
    }

    /**
     * Prihlaseni uzivatele
     */
    public function loginAction() {
        $this->_helper->layout->setLayout('login');
        $this->view->title = 'Přihlášení';

        $form = new LoginForm();
        //$form->setMethod('post');
        $this->view->loginform = $form;
    }

}
