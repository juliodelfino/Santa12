<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Controller extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->lang->switch_to(SessionUtil::getLanguage());
		
		date_default_timezone_set("Asia/Hong_Kong");
	//	date_default_timezone_set("Atlantic/Canary"); //zero timezone
		if ($this->allowAnonymous() && !SessionUtil::loggedIn())
		{
			//user validation code should not be executed for anonymous users
			return;
		}
		
		//check if user is not authenticated
		if (!SessionUtil::loggedIn()) 
		{
			if ($this->isAjaxRequest())
			{
				$this->output->set_status_header(401);
			}
			else
			{
			    SessionUtil::setLastVisitedPage(AppConfig::$url . $this->uri->uri_string());
				redirect(AppConfig::$url . 'login/', 'refresh');
			}
		}
		//check if user has not logged in on another computer
		$this->load->model('user_model');
		$user = SessionUtil::getUser();
		$userInDb = $this->user_model->get($user->userid);
		if (empty($userInDb))
		{
			SessionUtil::destroy();
			redirect(AppConfig::$url . 'login/', 'refresh');
		}
		if ($user->session_id != $userInDb->session_id)
		{
			SessionUtil::destroy();			
			if ($this->isAjaxRequest())
			{
				$this->output->set_status_header(401);
			}
			else
			{
			    SessionUtil::setLastVisitedPage(AppConfig::$url . $this->uri->uri_string());
				redirect(AppConfig::$url . 'login/', 'refresh');
			}
		}
		//check if user belongs to allowed roles		
		$roles = $this->allowedRoles();
		if (count($roles) > 0)
		{
			if (array_search($user->role, $roles) === false)
			{
				if ($this->isAjaxRequest())
				{
					$this->output->set_status_header(401);
				}
				else
				{
					$this->redirectToHome();
				}
			}
		}
	}
	
	//empty array means all roles are allowed; else
	//only roles specified in the array are allowed
	protected function allowedRoles()
	{
		//return array(Role::ADMIN);
		return array();
	}
	
	protected function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	protected function redirectToHome()
	{
		redirect(AppConfig::$url . "home/", 'refresh');
	}
	
	protected function annotateAsAjaxCall()
	{
		if (!$this->isAjaxRequest())
		{
			//$this->redirectToHome();
			show_404();
		}
	}
	
	protected function allowAnonymous()
	{
		return false;
	}
}

/* End of file App_Controller.php */
/* Location: ./system/application/libraries/App_Controller.php */