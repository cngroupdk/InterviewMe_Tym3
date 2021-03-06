<?php

/**
 * Plugin zajistuje autentifikaci uzivatele a presmerovani
 * Nastaveni je prebrano z application.ini s prefixem auth
 *
 * @see Zend_Auth_Adapter_DbTable
 */
class Application_Plugin_DbAuth extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @var array
     */
    private $_options;

    /**
     * Metoda vrati konkretni hodnotu z konfigurace
     * Pokud klic neni nalezen, vyhodime vyjimku
     *
     * @param string $key
     * @return mixed
     */
    private function _getParam($key)
    {
        if (is_null($this->_options))
        {
            $this->_options = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getApplication()->getOptions();
        }

        if (!array_key_exists($key, $this->_options['auth']))
        {
            throw new Zend_Controller_Exception("Param {auth.$key} not found in application.ini");
        }
        else
        {
            return $this->_options['auth'][$key];
        }
    }

    /**
     * Wrapper nad metodou _getParam
     * Umozni nam pristupovat ke konfiguraci primo pres $this
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->_getParam($key);
    }

    /**
     * Enter description here...
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // ziskame instanci redirector helperu, ktery ma starosti presmerovani
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');

        $auth = Zend_Auth::getInstance();
        // Stav o autentifikaci uzivatele (prihlaseni) se musi nekde udrzovat, vychozi zpusob je session
        // u session lze nastavit namespace, vychozi je Zend_Auth
        //$auth->setStorage(new Zend_Auth_Storage_Session('My_Auth'));

        if ($request->getParam('logout'))
        {
            // detekovano odhlaseni

            $auth->clearIdentity();

            // kvuli bezpecnosti provedeme presmerovani
            $redirector->gotoSimpleAndExit($this->failedAction, $this->failedController);
        }
        if ($request->getPost('login'))
        {

            $db = Zend_Db_Table::getDefaultAdapter();


            // Vytvarime instance adapteru pro autentifikaci
            // nastavime parametry podle naseho nazvu tabulky a sloupcu
            // treatment obsahuje pripadne pouzitou hashovaci funkci pro heslo, napr. SHA1

            $adapter = new Zend_Auth_Adapter_DbTable($db, $this->tableName, $this->identityColumn, $this->credentialColumn, $this->treatment);


            $form = new LoginForm();

            // validace se nezdari, napr. prazdny formular
            if (!$form->isValid($request->getPost()))
            {
                // FlashMessenger slouzi k uchovani zprav v session
                $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $flash->clearMessages();
                $flash->setNamespace("error")->addMessage("Please fill the login form!");
                $redirector->gotoSimpleAndExit($this->failedAction, $this->failedController, null, array('login-failed' => 1));
            }

            $username = $form->getValue($this->loginField);
            $password = $form->getValue($this->passwordField);

            // jmeno a heslo predame adapteru
            $adapter->setIdentity($username);
            $user = My_Model::get('Users')->fetchRow(array("username = ?" => $username));
            if($user == null){
                
                $redirector->gotoSimpleAndExit($this->failedAction, $this->failedController, null, array('login-failed' => 1));
            }
            $salt = $user->getSalt();
            $adapter->setCredential($password.$salt);

            // obecny proces autentifikace s libovolnym adapterem
            $result = $auth->authenticate($adapter);

            if ($auth->hasIdentity())
            {
                // Uzivatel byl uspesne overen a je prihlasen

                $identity = $auth->getIdentity();

                // identity obsahuje v nasem pripade ID uzivatele z databaze
                // muzeme napr. ulozit IP adresu, cas posledniho prihlaseni atd.

                $db->update($this->tableName, array(
                    'lognum' => new Zend_Db_Expr('lognum + 1'),
                    'ip' => $request->getServer('REMOTE_ADDR'),
                    'last_login' => new Zend_Db_Expr('NOW()'),
                    'browser' => $request->getServer('HTTP_USER_AGENT')),
                        $this->identityColumn . " = '$identity'");


                $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $flash->clearMessages();
                $flash->setNamespace("success")->addMessage("Success! You are logged in!");
                // presmerujeme
                $redirector->gotoSimpleAndExit($this->successAction, $this->successController);
            }
            else
            {
                // autentifikace byla neuspesna
                // FlashMessenger slouzi k uchovani zprav v session
                $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $flash->clearMessages();

                // vlozime do session rovnou chybove hlasky, ktere pak predame do view
                foreach ($result->getMessages() as $msg)
                {
                    $flash->setNamespace("error")->addMessage("Login failed, please try again!");
                }

                /*
                  // nicmene muzeme je nastavit podle konkretniho chyboveho kodu

                  if ($result == Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID)
                  {
                  // neplatne heslo
                  }
                  else if ($result == Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS)
                  {
                  // nalezeno vice uzivatelskych identit
                  }
                  else if ($result == Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND)
                  {
                  // identita uzivatele nenalezena
                  }
                 *
                 */

                $redirector->gotoSimpleAndExit($this->failedAction, $this->failedController, null, array('login-failed' => 1));
            }
        }
    }

}