<?php

/**
 * Plugin resi autorizaci uzivatele
 * Pokud uzivatel nema pristup do vybraneho controlleru/akce
 * je presmerovan na prihlasovaci obrazovku
 * do messengeru je pridana chybova hlaska
 */
class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $options = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getApplication()->getOptions();

        $config = new Zend_Config($options);

        $acl = new My_Acl($config);
        Zend_Registry::set('ACL', $acl);
        $role = 'guest';
        if (Zend_Auth::getInstance()->hasIdentity())
        {
            $foundUser = My_Model::get('Users')->fetchRow(array('username  = ?' => Zend_Auth::getInstance()->getIdentity()));
            $role = $foundUser->getRole();
        }
        Zend_Registry::set('ROLE', $role);
        
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $resource = $controller;
        $privilege = $action;

        if (!$acl->has($resource))
        {
            $resource = null;
        }

        if (is_null($privilege))
        {
            $privilege = 'index';
        }
        if (!$acl->isAllowed($role, $resource, $privilege))
        {

            $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
            $flash->clearMessages();


            $flash->addMessage('Access Denied');


            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
            //throw new Exception("Insufficient privilages (".$role.",".$resource.",".$privilege.")");
            $redirector->gotoSimpleAndExit('login', 'admin');
        }
    }
}