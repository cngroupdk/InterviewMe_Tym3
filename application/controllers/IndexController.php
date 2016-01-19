<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
       // $this->_helper->layout->setLayout('guest');
        /* Initialize action controller here */
    }
    private $applicantResultTranslation = array(
                    ''=>'Pending',
                    'invitedtointerview'=>'Invited to interview',
                    'waitingforresponse'=>'Waiting for response',
                    'accepted'=>'Accepted',
                    'rejected' =>'Rejected',
                    'hired'=>'Hired',
                    
                    'offerdeclined'=>'Offer declined',
                    'assigned'=>'Assigned',
                    'submitted'=>'Submitted',
                    'evaluated'=>'Evaluated'
                    );
    
		
    public function indexAction()
    {
        $technologiesIdNameArray = array();
        $technologiesToDashboard = array();
        $technologiesAll = My_Model::get('Technologies')->fetchAll();
        foreach ($technologiesAll as $technology) {
            if($technology->getDisplayOnDashboard() == 1){
                $technologiesToDashboard[] = $technology;
            }
            $technologiesIdNameArray[$technology->getId()] = $technology->getName();
        }
        //$technologies = My_Model::get('Technologies')->fetchAll(array('display_on_dashboard  = ?' => 1));
        $testsFilled = array();
        foreach($technologiesToDashboard as $technology){
            $tests = My_Model::get('Tests')->getCount($technology->getId());
            $testsFilled[$technology->getName()] = $tests;
        }
        $this->view->testsFilled = $testsFilled;
        $this->view->allApplicantCount = My_Model::get('Applicants')->getCountAllApplicants();
        $this->view->hiredApplicantCount = My_Model::get('Applicants')->getCountHiredApplicants();
        $this->view->rejectedApplicantCount = My_Model::get('Applicants')->getCountRejectedApplicants();
        
        
        $applicantsByResultRowset = My_Model::get('Applicants')->getApplicansByResult();
        $this->view->applicantsByResultTotalCount = 0;
        $applicantsByResult = array();
        foreach($applicantsByResultRowset as $applicantByResult){
            $applicantsByResult[$this->applicantResultTranslation[$applicantByResult->getResult()]] = $applicantByResult->getCount();
             $this->view->applicantsByResultTotalCount += $applicantByResult->getCount();
        }
        $this->view->applicantsByResult = $applicantsByResult;
        
        $applicantsByTechnologyRowset = My_Model::get('Applicants')->getApplicansByTechnology();
        $this->view->applicantsByTechnologyTotalCount = 0;
        $applicantsByTechnology = array();
        $i = 0;
        foreach($applicantsByTechnologyRowset as $applicantByTechnology){
            if($i<5){
                $applicantsByTechnology[$technologiesIdNameArray[$applicantByTechnology->getTechnologyId()]] = $applicantByTechnology->getCount();
            }else{
                if(!isset( $applicantsByTechnology["Other"])){
                    $applicantsByTechnology["Other"] = 0;
                }
                $applicantsByTechnology["Other"] += $applicantByTechnology->getCount();
            }
            $this->view->applicantsByTechnologyTotalCount +=  $applicantByTechnology->getCount();
            $i++;
        }
        $this->view->applicantsByTechnology = $applicantsByTechnology;
        //getApplicansByTechnology
        
        $testsByScoreRowset = My_Model::get('SessionTests')->getTestsByScore();
        $testsByScore = array();
        $i = 0;
        foreach($testsByScoreRowset as $testByScore){
            if(count($testsByScore)< 10){
                $testsByScore[$testByScore->getName()] = array("count"=>$testByScore->getCount(),"average"=>$testByScore->getAverage());
                continue;
            }
            $i++;
        }
        $this->view->testsByScore = $testsByScore;
        $this->view->testsByScoreExtra = $i;
        $this->view->title = "Dashboard";
    }

}

