<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends App_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('story_model');
		$this->load->model('like_model');
		$this->load->model('comment_model');
		$this->load->model('notif_model');
	}
	
	public function index()
	{	    
		$data = array();
		$data['title'] = "NSP Secret Santa 2012";
		$data['selectedNav'] = 'home';
		$me = SessionUtil::getProfile();
/*              Disallow the first-time viewers from viewing the feed.
		if (!$this->story_model->userHasStories($me->userid)) {
		    
		    $data['nickname'] = $me->nickname;
			$data['content'] = $this->load->view('noposts', $data, true);
			$this->load->view('layout/master', $data);
		    return;
		}
*/
		$stories = $this->story_model->getAll(0, 10);

		foreach ($stories as $story) {
		    
		    $likeUsers = $this->like_model->getByPostId($story->postid);
		    $iLike = false;
		    foreach ($likeUsers as $likeUser) {
		        if ($likeUser->userid == $me->userid) {
		            $iLike = true;
		            break;
		        }
		    }			
		    $story->htmlLike = PostUtil::likesToHtml($likeUsers);
		    $story->iLike = $iLike;
		    
		    $comments = $this->comment_model->getForPostId($story->postid, $me->userid);
		    $commentCount = count($comments);
		    for ($i = 0; $i < $commentCount; $i++)
		    {
		        $comments[$i]->hidden = ($i < $commentCount - 4);
		    }
		    $story->comments = $comments;
		    $story->viewMode = ($story->userid != $me->userid);
		}
		$data['stories'] = $stories;
		$data['notif_count'] = $this->notif_model->getUnreadCount($me->userid);
		$data['content'] = $this->load->view('home', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	public function get_feed() {
	    
	    $this->annotateAsAjaxCall();
	    
	    $lastPostId = $this->input->get('lastpostid');
	    $userId = $this->input->get('userid');
	    
	    if (empty($userId)) {
		    $stories = $this->story_model->getPrevious($lastPostId);
	    }
	    else {
	        $stories = $this->story_model->getPreviousByUserId($lastPostId, $userId);
	    }
		$me = SessionUtil::getProfile();

		foreach ($stories as $story) {
		    
		    $likeUsers = $this->like_model->getByPostId($story->postid);
		    $iLike = false;
		    foreach ($likeUsers as $likeUser) {
		        if ($likeUser->userid == $me->userid) {
		            $iLike = true;
		            break;
		        }
		    }			
		    $story->htmlLike = PostUtil::likesToHtml($likeUsers);
		    $story->iLike = $iLike;
		    
		    $comments = $this->comment_model->getForPostId($story->postid, $me->userid);
		    $commentCount = count($comments);
		    for ($i = 0; $i < $commentCount; $i++)
		    {
		        $comments[$i]->hidden = ($i < $commentCount - 4);
		    }
		    $story->comments = $comments;
		    $story->viewMode = ($story->userid != $me->userid);
		}
		
	    foreach ($stories as $story) 
        {
            echo $this->load->view('layout/wishlistentry', $story, true);
        }
	}
	
	public function updates() {
	    
	    $this->annotateAsAjaxCall();
	    $me = SessionUtil::getUser();
		$data = new stdClass();
	    $data->notifCount = $this->notif_model->getUnreadCount($me->userid);
	    $data->serverTimestamp = time();
	    echo json_encode($data);
	}
	
	//TODO: comment this
	private function insertDummyData() {
	
	    $xstory = $this->story_model->get(20);
	    $ystory = new stdClass();
	    $ystory->title = $xstory->title;
	    $ystory->description = $xstory->description;
	    $ystory->photo_file = $xstory->photo_file;
	    for ($i = 0; $i < 1000; $i++) {
	        $this->story_model->insert($ystory, 1);
	    }
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */