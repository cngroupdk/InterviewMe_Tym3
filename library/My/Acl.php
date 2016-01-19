<?php

/**
 * ACL strom
 *
 * ACL role i resource jsou nastaveny z application.ini s prefixem acl
 */
class My_Acl extends Zend_Acl
{

    /**
     *
     * @param Zend_Config $config
     */
    public function __construct(Zend_Config $config)
    {
        $guestRole = "guest";
        $reviewerRole = "reviewer";
        $adminRole = "admin";
        $this->addRole(new Zend_Acl_Role($guestRole))
            ->addRole(new Zend_Acl_Role($reviewerRole),$guestRole)//reviewer inherit guests permissions
            ->addRole(new Zend_Acl_Role($adminRole));
        
        $this->add(new Zend_Acl_Resource("admin"));
        $this->add(new Zend_Acl_Resource("applicant"));
        $this->add(new Zend_Acl_Resource("error"));
        $this->add(new Zend_Acl_Resource("index"));
        $this->add(new Zend_Acl_Resource("position"));
        $this->add(new Zend_Acl_Resource("publictest"));
        $this->add(new Zend_Acl_Resource("test"));
        $this->add(new Zend_Acl_Resource("testquestion"));
        $this->add(new Zend_Acl_Resource("user"));
        
        $this->add(new Zend_Acl_Resource("editApplicantWithoutAccessToData"));
        
        
         $this->allow($adminRole);//Admin can do everything
         
         $this->allow($guestRole, "admin","login");
         $this->allow($guestRole, "error");
         $this->allow($guestRole, "publictest","index");
         $this->allow($guestRole, "publictest","save");
         
         
         $this->allow($reviewerRole, "applicant");//Allow all actions for controller applicant
         $this->allow($reviewerRole, "admin","logout");
         $this->allow($reviewerRole, "admin","index");
         $this->allow($reviewerRole, "test");
         $this->allow($reviewerRole, "index");
         $this->allow($reviewerRole, "applicant","edit");
         $this->allow($reviewerRole, "testquestion");
         $this->allow($reviewerRole, "publictest","edit");
         $this->allow($reviewerRole, "user","photo");
         
         $this->deny($reviewerRole, "test","delete");
         $this->deny($reviewerRole, "applicant","delete");
         $this->deny($reviewerRole, "applicant","editAdvancedInfo");
         //Message: Insufficient privilages (reviewer,,index) | Review
         
    }

    /**
     * Nacte uzivatelske role
     *
     * @param Zend_Config $roles
     */
    private function _addRoles($roles)
    {
        if (empty($roles))
        {
            throw new Exception('Acl roles must be specified in application.ini');
        }

        foreach ($roles as $name => $parents)
        {
            if (!$this->hasRole($name))
            {
                if (empty($parents))
                {
                    $parents = null;
                }
                else
                {
                    $parents = explode(',', $parents);
                }

                $this->addRole(new Zend_Acl_Role($name), $parents);
            }
        }
    }

    /**
     * Nacte resource
     *
     * @param Zend_Config $resources
     */
    private function _addResources($resources)
    {
        if (empty($resources))
        {
            throw new Exception('Acl resources must be specified in application.ini');
        }

        foreach ($resources as $permissions => $controllers)
        {
            foreach ($controllers as $controller => $actions)
            {

                if ($controller == 'all')
                {
                    $controller = null;
                }
                else
                {
                    if (!$this->has($controller))
                    {
                        $this->add(new Zend_Acl_Resource($controller));
                    }
                }

                if (!($actions instanceof Zend_Config))
                {
                    $actions = array('all' => $actions);
                }

                foreach ($actions as $action => $role)
                {
                    if ($action == 'all')
                    {
                        $action = null;
                    }
                    if ($permissions == 'allow')
                    {
                        $this->allow($role, $controller, $action);
                    }
                    if ($permissions == 'deny')
                    {
                        $this->deny($role, $controller, $action);
                    }
                }
            }
        }
    }

}
