<?php

/**
 * User controller
 *
 */
class UserController extends Zend_Controller_Action {

    /**
     * A list of all users
     *
     */
    public function indexAction() {
        $users = My_Model::get('Users')->fetchAll();
        $this->view->users = $users;
        $this->view->title = 'List of Users';
    }

    /**
     * A detail of a single user
     *
     */
    public function detailAction() {
        $userId = $this->_getParam('id');
        $user = My_Model::get('Users')->getById($userId);

        if (!$user) {
            throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
        }
        $this->view->title = $user->getUsername();
        $this->view->user = $user;
    }
    
    public function editAction() {
        $record = null;
        $photoFilename = null;

        $userId = $this->_request->getParam('id');
        if (!empty($userId)) {
            $record = My_Model::get('Users')->getById($userId);
            if (!$record) {
                throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
            }
            $this->view->userId = $userId;
        }


        $form = new UserForm();
        $form->setAction($this->_helper->url->url());


        if ($record === null) {
            $this->view->title = 'Add User';
        } else {
            $this->view->title = 'Edit User';
            $form->setModifyMode();
        }

        $this->view->form = $form;
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formValues = $form->getValues();
                $foundUser = My_Model::get('Users')->fetchRow(array("username = ?"=>$formValues["username"]));
                if($foundUser != null && $foundUser->getId() != $userId){
                     $form->getElement('username')->addError('This username is taken');
                     $form->markAsError();
                     return;
                }
                
                //XXX: Je to dobytčárna
                if ($form->photo->receive()) {
                    $photo = $form->photo;
                    $oldFullPath = $photo->getFileName();
                    $path_parts = pathinfo($oldFullPath);
                    if ($path_parts) {
                        $photoFilename = $photo->getHash('md5') . '.' . $path_parts['extension'];
                        $newFullPath = $path_parts['dirname'] . '/' . $photoFilename;
                        rename($oldFullPath, $newFullPath);
                    }
                }

                if ($record === null) {
                    $record = My_Model::get('Users')->createRow();
                    if ($photoFilename) {
                        $record->setPhotoFilename($photoFilename);
                    }
                    $record->updateFromArray($formValues, true);
                } else {
                    if ($photoFilename) {
                        $record->setPhotoFilename($photoFilename);
                    }
                    $record->updateFromArray($formValues,  false); //do not update created on value
                }
                
                //Zend_Debug::dump($formValues);
                //echo '================================================================<br />';
                //Zend_Debug::dump($formValues);
                //echo '========================PHOTO=========================<br />';
                //$var = file_get_contents($form->photo);
                //Zend_Debug::dump($var);

                $this->_helper->flashMessenger->setNamespace("success")->addMessage("Your changes have been saved!");

                $this->_helper->redirector->gotoRoute(array('controller' => 'user'), 'default', true);
            }
        } else {
            if ($record !== null) {
                $form->populate($record->toArray());
            }
        }

       
    }

    public function deleteAction() {

        if ($this->_request->isPost()) {
            $id = $this->_getParam('id');
            if (!empty($id)) {
                $user = My_Model::get('Users')->getById($id);
                $applicantsInterviewed = My_Model::get('Applicants')->fetchAll(array("interviewed_by = ?"=>$id));
                foreach ($applicantsInterviewed as $applicant){
                    $applicant->updateFromArray(array("interviewed_by"=>null));
                }
                $applicantsInterviewed = My_Model::get('Applicants')->fetchAll(array("interviewed_by2 = ?"=>$id));
                foreach ($applicantsInterviewed as $applicant){
                    $applicant->updateFromArray(array("interviewed_by2"=>null));
                }
                $applicantsInterviewed = My_Model::get('Applicants')->fetchAll(array("interviewed_by3 = ?"=>$id));
                foreach ($applicantsInterviewed as $applicant){
                    $applicant->updateFromArray(array("interviewed_by3"=>null));
                }
                
                if ($user) {
                    $user->deleteUser();
                    $this->_helper->flashMessenger->setNamespace("success")->addMessage("The user was successfully deleted!");
                }
            }
            $this->_helper->redirector->gotoRoute(array('controller' => 'user'), 'default', true);
        }
    }
    
    /**
     * Show photo.
     */
    public function photoAction() {
        $userId = $this->_getParam('id');
        $user = My_Model::get('Users')->getById($userId);

        $file = Zend_Registry::get('uploadPath') . '/' . $user->getPhotoFilename();
        if (is_file($file)) {
            header('Content-Type: image/jpeg');//FIXME: pevně nastaveno na jpeg, i když uploader i cesta by zvládli obecné typy
            readfile($file);

            // disable layout and view
            $this->view->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $file = APPLICATION_PATH.'/../public/images/user.png';
            
            header('Content-Type: image/png');
            readfile($file);

            // disable layout and view
            $this->view->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            
            //throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
        }
    }

}
