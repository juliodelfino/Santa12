<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friends extends App_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('profile_model');
		$this->load->model('like_model');
		$this->load->model('notif_model');
	}
	
	public function index()
	{	    
		$data = array();
		$data['title'] = "NSP Secret Santa 2012";
		$data['selectedNav'] = 'friends';
		
		$profiles = $this->profile_model->getRandom();
		ProfileUtil::completeProfile($profiles);
		$data['users'] = $profiles;
		$data['content'] = $this->load->view('friends', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	public function get()
	{
	    $offset = $this->input->get('offset');
	    
		$profiles = $this->profile_model->getRandom($offset);
		ProfileUtil::completeProfile($profiles);
	    foreach ($profiles as $user) {
        
            echo $this->load->view('layout/friendentry', $user, true);
        }
	}
	
	public function likers() {
		
		$postId = $this->input->get('postid');
		$users = $this->like_model->getByPostIdMore($postId);
		$content = '';
		foreach($users as $user) {
			ProfileUtil::completeProfile($user);
			$content .= $this->load->view('layout/userentry-dialog', $user, true);
		}
		echo $content;
	}
	
	public function search() {
	    
	    $text = $this->input->get('text');
		$text = str_replace("%20", "|", $text);
		$profiles = array();
		if (empty($text)) {
		    $profiles = $this->profile_model->getRandom();
		}
		else {
		    $profiles = $this->profile_model->search($text);
		}
		ProfileUtil::completeProfile($profiles);
				
		$content = '';
	    foreach ($profiles as $user) {
        
            $content .= $this->load->view('layout/friendentry', $user, true);
        }
        echo $content;
	}
	
	public function reminder_email()
	{
	    $me = SessionUtil::getProfile();
	    $data = new stdClass();
	    $data->giftee_email = $me->giftee_email;
	    echo  $this->load->view('reminder_email', $data, true);
	}
	
	public function send_mail()
	{
	    $recipient = $this->input->post('email');
	    $message = $this->input->post('message');
	    $success = EmailUtil::sendReminderMail($recipient, $message);
	    if ($success) {
	        
	        $me = SessionUtil::getUser();
	        
	        $data = new stdClass();
	        $data->giftee_email = $recipient;
	        $this->profile_model->update($me->userid, $data);
	        
			$notifInfo = new stdClass();
			$notif = new stdClass();
            $notifInfo->recipient = $recipient;
            $notif->for_userid = $me->userid;
            $notif->type = NotifType::SENT_REMINDER;
            $notif->info = json_encode($notifInfo);
            $this->notif_model->insert($notif);
	    }
	    return $success;
	}
	
    private function insertDummyData()
    {     
	    $this->load->model('user_model');
	    $pass =  md5('abc123');
	    for ($i = 0; $i < 300; $i++) {
	        
    	    $newUser = new stdClass();
    	    $newUser->email = 'nsp.user' . $i . '@ntsp.nec.co.jp';
    	    $newUser->password = $pass;
    	    
    	    $newProfile = new stdClass();
    	    $newProfile->nickname = 'Test User ' . $i;
    	    $newProfile->fullname = 'Test User ' . $i;
	        
    	    $this->user_model->insert($newUser);
    	    $newProfile->userid = $newUser->userid;
    	    $this->profile_model->insert($newProfile);
	    }
    }
}

/* End of file friends.php */
/* Location: ./application/controllers/friends.php */