<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends App_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('notif_model');
	}
	
	public function index()
	{
		$data = array();
		$data['title'] = "NSP Secret Santa 2012";
		$me = SessionUtil::getUser();
		
		$data['notifs'] = $this->notif_model->getForUserId($me->userid, 0, 50);
		$data['content'] = $this->load->view('notifs', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	public function get_latest()
	{
		$data = new stdClass();
		$me = SessionUtil::getUser();
		
		$notifs = $this->notif_model->getUnreadForUserId($me->userid);
		if ($notifs == null || ($notifs != null && count($notifs) < 5)) {
		    
		    $notifs = $this->notif_model->getForUserId($me->userid);
		}
	    $this->notif_model->setAsRead($notifs);
		$data->notifs = $notifs;

		foreach ($notifs as $notif) {
	        echo NotifType::toHtml($notif, false);
		}
	}
	
	//TODO: comment this
	private function createDummyNotifs()
	{
		
        $notif = new stdClass();
        $notif->for_userid = 1;
        $notif->info = '{"userid":7,"commentid":"63","storyid":"59"}';
        $notif->isread = false;
        $notif->type = NotifType::LIKE_ON_COMMENT;
        for ($i = 0; $i < 1000; $i++) {
       //     $notif = unserialize(serialize($notif));
            $this->notif_model->insert($notif);
        }
	}
}

/* End of file notifications.php */
/* Location: ./application/controllers/notifications.php */