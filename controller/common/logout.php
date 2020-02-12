<?php       
class ControllerCommonLogout extends Controller {   
	public function index() { 
		log_activity("Account Logout", "A user logged-out his account.", $this->session->data['user_id'], $this->session->data['group_id']);
    	$this->user->logout();
 		unset($this->session->data['group_id']);
 		unset($this->session->data['token']);

		$this->redirect(HTTPS_SERVER . 'index.php?route=common/login');
  	}
}  
?>