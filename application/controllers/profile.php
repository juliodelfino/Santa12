<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends App_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('profile_model');
		$this->load->model('story_model');
		$this->load->model('like_model');
		$this->load->model('comment_model');
	}
	
	public function index()
	{	    
		$data = array();
	    $selectedUserId = $this->input->get('id');
		$me = SessionUtil::getProfile();
	    if (!empty($selectedUserId)) 
	    {
	        $profile = $this->profile_model->get($selectedUserId);
	        if (empty($profile)) {
	             show_404();
	             return;   
	        }
	        ProfileUtil::completeProfile($profile);
	        $data['viewMode'] = $selectedUserId != $me->userid;
	    }
	    else 
	    {
	        $profile = SessionUtil::getProfile();
	        $data['viewMode'] = false;
	    }
		
		$stories = $this->story_model->getByUserId($profile->userid, 0, 5);
		foreach ($stories as $story) 
		{
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
		}
		
        $data['user'] = $profile;
		$data['title'] = $profile->nickname;
		$data['selectedNav'] = 'profile';	
		$data['userHasStories'] = $this->story_model->userHasStories($me->userid);
		$data['stories'] = $stories;
		$data['content'] = $this->load->view('profile', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	public function photo()
	{
		$data = array();
	    $action = $this->input->post('action');
		$profile = SessionUtil::getProfile();
	    if ($action == 'save') {
	        $resultData = FileUtil::uploadFile('photo', true);
	        if ($resultData->success) {
	            
				$newProfile = new stdClass();
    			$newProfile->photo_file = $resultData->file_name;
    			$this->profile_model->update($profile->userid, $newProfile);
    			$profile->photo_file = $newProfile->photo_file;
	            redirect(AppConfig::$url . 'profile/', 'refresh');
				return;
	        }
	        else {
	            $data['errorMessage'] = $resultData->message;
	        }
	    }
		$data['title'] = "NSP Secret Santa 2012";
		$data['selectedNav'] = 'profile';
		$data['profile'] = $profile;
		$data['content'] = $this->load->view('editprofilepic', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	public function edit()
	{
	    $action = $this->input->post('action');
	    if ($action == 'save') {
	        return $this->save();
	    }
		$data = array();
		$data['title'] = "NSP Secret Santa 2012";
		$data['selectedNav'] = 'profile';
		$data['profile'] = SessionUtil::getProfile();
		$data['content'] = $this->load->view('editprofile', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	private function save()
	{
	    $profile = SessionUtil::getProfile();
		$newData = new stdClass();
	    $newData->nickname = $this->input->post('nickname');
	    $newData->phone = $this->input->post('phone');
	    $newData->giftee_email = $this->input->post('giftee_email');
	    $newData->giftee_notif = ($this->input->post('giftee_notif') === 'true');
	    $newData->comment_notif = ($this->input->post('comment_notif') === 'true');
	    
	    $this->profile_model->update($profile->userid, $newData);
	    
	    $profile = $this->profile_model->get($profile->userid);
		ProfileUtil::completeProfile($profile);
	    SessionUtil::setProfile($profile);

	    echo '{ "success" : true, "redirect_link" : "' . AppConfig::$url . 'profile",'
	    	. ' "message" : "You have successfully updated your profile." }';
	    return true;
	}
}

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */