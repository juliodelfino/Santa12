<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends App_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('profile_model');
	}

	function index()
	{
		$user = SessionUtil::getUser();
		$profile = $this->profile_model->get($user->userid);
		if ($profile->first_time)
		{
		    $profile2 = new stdClass();
			$profile2->first_time = false;
			$this->profile_model->update($user->userid, $profile2);
		}
		SessionUtil::destroy();
		redirect(AppConfig::$url, 'refresh');
	}
}

/* End of file logout.php */
/* Location: ./application/controllers/logout.php */