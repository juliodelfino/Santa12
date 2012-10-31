<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends App_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('profile_model');
	}
	
	protected function allowAnonymous()
	{
		return true;
	}
	
	public function index()
	{
		$input = new stdClass();
		$input->email = trim($this->input->post('username'));
		$input->password = $this->input->post('password');
		$loginData = (object)array('successful' => false, 'errorMsg' => '');
		if (!empty($input->email)) 
		{
			$user = unserialize(serialize($input));
			$userEmail = $user->email;
			$userEmail .= !StringUtil::contains('@', $user->email) ? '@ntsp.nec.co.jp' : '';

			if ($this->user_model->getByUsername($userEmail) != null)
			{
				$user->email = $userEmail;
				$loginData = $this->user_model->login($user);
			}
			else {
				$loginData = $this->tryRegisterNspUser($user);
			}
		}
		if ($loginData->successful) 
		{		
			$user = $loginData->user;
			$userSession = new stdClass();
			$userSession->session_id = SessionUtil::id();
			$userSession->ip_address = SessionUtil::visitorIpAddress();
			$userSession->last_login_date = DateUtil::nowDbDate();
			$user->session_id = $userSession->session_id;
			$this->user_model->update($user->userid, $userSession);
			
			$user->username = StringUtil::contains('@', $input->email) ?
			    substr($input->email, 0, strpos($input->email, '@')) : $input->email;
			$user->ldapPassword = base64_encode($input->password);
			$profile = $this->profile_model->get($user->userid);
			ProfileUtil::completeProfile($profile);
			SessionUtil::setAuthenticatedUser($user);
			SessionUtil::setProfile($profile);
			$link = SessionUtil::getLastVisitedPage();
			if (!isset($link)) 
			{
				$link = AppConfig::$url . "home/";
			}
			redirect($link, 'refresh');
		}
		else {
			if (SessionUtil::loggedIn())
			{
				parent::redirectToHome();
			}
			if (empty($input->email))
			{
				$input = SessionUtil::getUser();	
			}
			$data['user'] = $input;
			$data['errorMsg'] = $loginData->errorMsg;
			$data['title'] = 'NSP Secret Santa 2012';
			
    		$this->load->view('login', $data);
		}
	}
	
	public function tryRegisterNspUser($user)
	{
		$user->email = StringUtil::contains('@', $user->email) ?
			    substr($user->email, 0, strpos($user->email, '@')) : $user->email;
	    $loginData = (object)array('successful' => false, 'errorMsg' => '');
	    if (Ldap::isValidLdapUser($user->email, $user->password)) 
	    {
			$dbUser = new stdClass();
			$dbUser->email = $user->email . '@ntsp.nec.co.jp';
			$dbUser->password = md5($user->password);
			
			$userid = $this->user_model->insert($dbUser);
			$user->userid = $userid;
			$nspUser = Ldap::getLdapUserInfo($user->email);
			
			$profile = new stdClass();
			$profile->userid = $userid;
			$profile->fullname = $nspUser->fullname;
			$profile->nickname = $nspUser->fullname;
			
			$this->profile_model->insert($profile);
			
			$loginData->user = $user;
			$loginData->successful = true;
	        //TODO: add to users and profiles table
	    }
		else 
		{
			$loginData->errorMsg = 'The username or email is incorrect. Please try again.';
		}
	    return $loginData;
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */