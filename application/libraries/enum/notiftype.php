<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NotifType {

	const NEW_POST = 1;
	const UPDATE_POST = 2;
	const COMMENT = 3;
	const LIKE = 4;
	const LIKE_ON_COMMENT = 5;
	const SENT_REMINDER = 6;
	const TAG_POST = 7;
	const TAG_COMMENT = 8;
	
	private static $instance;
	
	private static function getInstance() {
	    
	    if (self::$instance == null) {
	        self::$instance = new NotifType();
	    }
	    return self::$instance;
	}
	
	private $ci;
	
	function __construct()
	{
		$this->ci = get_instance();
		$this->ci->load->model('story_model');	
		$this->ci->load->model('profile_model');
		$this->ci->load->model('notif_model');
	}
	
	function doParse($notif, $forMainPage = true) {
	    
        $ci = $this->ci;
		$me = SessionUtil::getUser();
		
		$data = new stdClass();
		$user = new stdClass();
		$info = json_decode($notif->info);
		
		if ($notif->type == NotifType::NEW_POST) {
		
			$user = $ci->profile_model->get($info->userid);
			$story = $ci->story_model->get($info->postid);
			ProfileUtil::completeProfile($user);
			$data->notifText = html_b($user->nickname) . ' added a new wishlist item "' . $story->title . '".';
		}
		else if ($notif->type == NotifType::UPDATE_POST) {
		
			$user = $ci->profile_model->get($info->userid);
			$story = $ci->story_model->get($info->postid);
			ProfileUtil::completeProfile($user);
			$data->notifText = html_b($user->nickname) . ' updated his/her wishlist item "' . $story->title . '".';
		}
		else if ($notif->type == NotifType::COMMENT) {
		
			$user = $ci->profile_model->get($info->userid);
			ProfileUtil::completeProfile($user);		
			$data->notifText = html_b($user->nickname) . ' commented on your wishlist item.';
		}
		else if ($notif->type == NotifType::LIKE) {
		
			$user = $ci->profile_model->get($info->userid);
			ProfileUtil::completeProfile($user);
			$data->notifText = html_b($user->nickname) . ' likes your wishlist item.';
		}
		else if ($notif->type == NotifType::LIKE_ON_COMMENT) {
		
			$user = $ci->profile_model->get($info->userid);
			ProfileUtil::completeProfile($user);
			$story = $ci->story_model->get($info->storyid);
			$owner = 'your';
			if ($story->userid == $user->userid) {
			    $owner = 'his/her';
			}
			else if ($story->userid != $me->userid) {
			    $owner = html_b($story->nickname . "'s");
			}
			$data->notifText = html_b($user->nickname) . " likes your comment on $owner wishlist item.";
		}
		else if ($notif->type == NotifType::SENT_REMINDER) {
		    
		    $recipientName = $info->recipient;
		    $user = $ci->profile_model->getByEmail($info->recipient);
		    if ($user == null) {
		        $user = new stdClass();
		    }
		    ProfileUtil::completeProfile($user);
		    if (!empty($user->nickname))  {
		        $recipientName = $user->nickname;
		    }
		    $data->notifText = 'You have sent a reminder email to ' . $recipientName;
		    $info->postid = 0;
		}
		else if ($notif->type == NotifType::TAG_POST) {
		
			$user = $ci->profile_model->get($info->userid);
			ProfileUtil::completeProfile($user);
			$data->notifText = html_b($user->nickname) . " tagged you in his/her post.";
		}
		else if ($notif->type == NotifType::TAG_COMMENT) {
		
			$user = $ci->profile_model->get($info->userid);
			ProfileUtil::completeProfile($user);
			$data->notifText = html_b($user->nickname) . " tagged you in a comment.";
		}

        if (!$forMainPage && strlen($data->notifText) > 85) {
            $data->notifText = substr($data->notifText, 0, 85) . '...';
        }		
		$data->isread = $notif->isread;
		$data->postid = $notif->type == NotifType::LIKE_ON_COMMENT? $info->storyid : $info->postid;
		if (empty($notif->date)) {
			$notif->date = Time::now();
		}
		$data->timestamp = Time::toTimeRelativeNow($notif->date);
		$data->photo_file = $user->photo_file;
		return $data;
	}
	
	function convertToHtml($notif, $forMainPage) {

        $ci = $this->ci;
        $data = $this->doParse($notif, $forMainPage);
		$view = $forMainPage ? 'layout/notifentry' : 'layout/notifentry-small';
		return $ci->load->view($view, $data, true);
	}
	
	static function toHtml($notif, $forMainPage = true) {
	
	    $parser = NotifType::getInstance();
	    return $parser->convertToHtml($notif, $forMainPage);
	}
	
	static function parse($notif) {
	
	    $parser = NotifType::getInstance();
	    return $parser->doParse($notif);
	}
}

/* End of file notiftype.php */
/* Location: ./application/libraries/enum/notiftype.php */