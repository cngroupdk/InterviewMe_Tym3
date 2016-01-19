<?php
class Zend_View_Helper_Messages extends Zend_View_Helper_Abstract {

    public function messages() {
    	$flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    	
    	$text  = '';
    	$redPop = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
    	$greenPop = '<div class="alert alert-success alert-dismissible fade in" role="alert">';
    //	$bluePop = '<div class="alert alert-info alert-dismissible fade in" role="alert">';
    	
    	$messageTypes = array('success','error');
    	$messageColorTypes = array($greenPop,$redPop);
	
    	for($i = 0; $i < count($messageTypes); ++$i) {

    		if (count($flashMessenger->setNamespace($messageTypes[$i])->hasMessages()) > 0) {

    			foreach ($flashMessenger->getMessages() as $message) {
    				$text .= $messageColorTypes[$i]; //<div>
    				$text .='<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    				<span aria-hidden="true">×</span>
					</button>';
    				$text .= '<div class="message">';
    				$text .= $this->view->translate($message);
    				$text .= '</div></div>';
    			}
    		}
    	}
    	return $text;
    }
    
}
