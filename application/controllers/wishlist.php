<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wishlist extends App_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('story_model');
		$this->load->model('like_model');
		$this->load->model('comment_model');
		$this->load->model('notif_model');
		$this->load->model('profile_model');
	}
	
	public function index()
	{ 
	    $postid = $this->input->get('id');
	    if (empty($postid)) {
	        show_404();
	    }
		$story = $this->story_model->get($postid);
		if ($story == null) {
		    show_404();
		}
        
		$me = SessionUtil::getProfile();
		    
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
	    $story->viewMode = $story->userid != $me->userid;
	    
        $data = array();
		$data['title'] = $story->title;
		$data['selectedNav'] = 'home';
		$data['stories'] = array($story);
		$data['noFeed'] = true;
		$data['content'] = $this->load->view('home', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	public function post()
	{
	    $action = $this->input->post('action');
	    if ($action == 'save') {
	        return $this->addPost();
	    }
		$data = array();
		$data['title'] = "NSP Secret Santa 2012";
		$data['story'] = (object)array('title' => '', 'description' => '', 
			'photo_file' => '', 'postid' => 0);
		$data['editMode'] = false;
		$data['content'] = $this->load->view('postwishlist', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	private function addPost()
	{
	    $title = $this->input->post('item_name');
	    $description = $this->input->post('description');
	    $photo = $_FILES['photo']['name'];
	    
		$data = new stdClass();
	    $data->title = StringUtil::filterContent($title);
	    $data->description = StringUtil::filterContent($description);	    
	    
	    if (!empty($photo))
	    {
    	    $resultData = FileUtil::uploadFile('photo');
    	    if ($resultData->success) {
    	        $data->photo_file = $resultData->file_name;
    	    }
	    }
	    $user = SessionUtil::getUser();
	    $postid = $this->story_model->insert($data, $user->userid);
	    
	    $notifUser = $this->profile_model->getByGifteeEmail($user->email);
	    if ($notifUser != null) 
	    {
			$info = new stdClass();
			$notif = new stdClass();
	        $info->userid = $user->userid;
	        $info->postid = $postid;
	        $notif->for_userid = $notifUser->userid;
	        $notif->type = NotifType::NEW_POST;
	        $notif->isread = false;
	        $notif->info = json_encode($info);
	        $this->notif_model->insert($notif);
	        
	        if ($notifUser->giftee_notif) {
	            EmailUtil::sendNewPostNotification($notifUser->email, $notif);
	        }
	    }		
		$this->notifyTaggedUsers($postid, $data->description);
	    
	    redirect(AppConfig::$url . 'profile/', 'refresh');
	}
	
	
	
	public function edit()
	{
	    $action = $this->input->post('action');
	    if ($action == 'save') {
	        return $this->saveChanges();
	    }
		$postid = $this->input->get('id');
		if (empty($postid))
		{
			show_404();
			return;
		}
		$story = $this->story_model->get($postid);
		if ($story == null) 
		{
			show_404();
			return;
		}
		
		$me = SessionUtil::getUser();
		if ($story->userid != $me->userid)
		{
			show_404();
			return;
		}
		$data = array();
		$data['title'] = "NSP Secret Santa 2012";
		$data['story'] = $story;
		$data['editMode'] = true;
		$data['content'] = $this->load->view('postwishlist', $data, true);
		$this->load->view('layout/master', $data);
	}
	
	private function saveChanges()
	{
	    $postid = $this->input->post('postid');
		if (empty($postid)) $postid = '0';
		
		$story = $this->story_model->get($postid);
		if ($story == null) 
		{
			show_404();
			return;
		}   
	    
	    $title = $this->input->post('item_name');
	    $description = $this->input->post('description');
	    
		$data = new stdClass();
	    $data->title = StringUtil::filterContent($title);
	    $data->description = StringUtil::filterContent($description);	
	    $data->photo_file = $story->photo_file;
	    $photo = $_FILES['photo']['name'];   
	    
	    if (!empty($photo))
	    {
    	    $resultData = FileUtil::uploadFile('photo');
    	    if ($resultData->success) {
    	        $data->photo_file = $resultData->file_name;
    	    }
		}
	    
	    $user = SessionUtil::getUser();
	    $this->story_model->update($postid, $data);
	
	    $notifUser = $this->profile_model->getByGifteeEmail($user->email);
	    if ($notifUser != null) 
	    {
			$info = new stdClass();
			$notif = new stdClass();
	        $info->userid = $user->userid;
	        $info->postid = $postid;
	        $notif->for_userid = $notifUser->userid;
	        $notif->type = NotifType::UPDATE_POST;
	        $notif->isread = false;
	        $notif->info = json_encode($info);
	        $this->notif_model->insert($notif);
	        
	        if ($notifUser->giftee_notif) {
	            EmailUtil::sendPostUpdateNotification($notifUser->email, $notif);
	        }
	    }
	    
	    redirect(AppConfig::$url . 'profile/', 'refresh');
	}
	
	public function delete()
	{
	    $this->annotateAsAjaxCall();
	    $postid = $this->input->post('postid');
		if (empty($postid)) $postid = '0';
		
		$story = $this->story_model->get($postid);
		if ($story == null) 
		{
			show_404();
			return;
		}
		
	    $this->story_model->delete($postid);
	    $this->notif_model->deleteHavingPostId($postid);
	}
	
	public function delete_comment()
	{
	    $this->annotateAsAjaxCall();
	    $postid = $this->input->post('postid');
		if (empty($postid)) $postid = '0';
		
		$comment = $this->comment_model->get($postid);
		if ($comment == null) 
		{
			show_404();
			return;
		}
		
	    $this->comment_model->delete($postid);
	    $this->notif_model->deleteHavingPostId($comment->for_postid, NotifType::COMMENT);
	}
	
	public function like()
	{
	    $this->annotateAsAjaxCall();
		$data = new stdClass();
	    $data->postid = $this->input->post('postid');
	    $user = SessionUtil::getUser();
	    $data->userid = $user->userid;
	    $story = $this->story_model->get($data->postid);
	    $iLike = false;
	    
	    if ($story != null) {
    	    $iLike = $this->like_model->toggleLike($data);
    	    $this->story_model->updateDateModified($story->postid);
    	    
    	    if ($iLike && $story->userid != $user->userid) {
				$notifInfo = new stdClass();
				$notif = new stdClass();
        	    $notifInfo->userid = $user->userid;
                $notifInfo->postid = $story->postid;
                $notif->for_userid = $story->userid;
                $notif->type = NotifType::LIKE;
                $notif->info = json_encode($notifInfo);
                $this->notif_model->insert($notif);
    	    }
	    }
	    
	    $likeUsers = $this->like_model->getByPostId($data->postid);
		
		$json = new stdClass();
	    $json->htmlLike = PostUtil::likesToHtml($likeUsers);
	    $json->iLike = $iLike;
	    
	    echo json_encode($json);
	}
	
	public function like_comment()
	{
	    $this->annotateAsAjaxCall();
		$data = new stdClass();
	    $data->postid = $this->input->post('postid');
	    $user = SessionUtil::getUser();
	    $data->userid = $user->userid;
	    $comment = $this->comment_model->get($data->postid);
	    $iLike = false;
	    
	    if ($comment != null) {
    	    $iLike = $this->like_model->toggleLike($data);
    	    $this->story_model->updateDateModified($comment->for_postid);
    	    
    	    if ($iLike && $comment->userid != $user->userid) {
				$notifInfo = new stdClass();
				$notif = new stdClass();
        	    $notifInfo->userid = $user->userid;
                $notifInfo->commentid = $comment->postid;
                $notifInfo->storyid = $comment->for_postid;
                $notif->for_userid = $comment->userid;
                $notif->type = NotifType::LIKE_ON_COMMENT;
                $notif->info = json_encode($notifInfo);
                $this->notif_model->insert($notif);
    	    }
	    }
	    
	    $likeUsers = $this->like_model->getByPostId($data->postid);
		
		$json = new stdClass();
	    $json->likeCount = count($likeUsers);
	    $json->iLike = $iLike;
	    
	    echo json_encode($json);
	}
	
	public function comment()
	{
	    $this->annotateAsAjaxCall();
		$data = new stdClass();
	    $data->for_postid = $this->input->post('postid');
	    $data->text = $this->input->post('text');
	    $data->text = StringUtil::filterContent($data->text);
		$prevCommentId = $this->input->post('prevpostid');
		if (empty($prevCommentId)) {
		    $prevCommentId = 0;
		}
	    $user = SessionUtil::getProfile();
	    $story = $this->story_model->get($data->for_postid);
		
	    if ($story != null) {
    	    $this->comment_model->insert($data, $user->userid);
    	    $this->story_model->updateDateModified($story->postid);
    	    
    	    if ($story->userid != $user->userid) {
				$notifInfo = new stdClass();
				$notif = new stdClass();
        	    $notifInfo->userid = $user->userid;
                $notifInfo->postid = $story->postid;
                $notif->for_userid = $story->userid;
                $notif->type = NotifType::COMMENT;
	            $notif->isread = false;
                $notif->info = json_encode($notifInfo);
                $this->notif_model->insert($notif);
                
	            $owner = $this->profile_model->get($story->userid);
    	        if ($owner->comment_notif) {
    	            EmailUtil::sendCommentNotification($owner->email, $notif, $data->text);
    	        }
    	    }
	    }
		
		$this->notifyTaggedUsers($data->for_postid, $data->text, false);

		$json = new stdClass();
	    $json->comments = $this->comment_model->getForPostId($data->for_postid, $user->userid, $prevCommentId);
	    foreach($json->comments as $comment)
	        $comment->hidden = false;
	    
	    $content = '';
		foreach ($json->comments as $comment) {
		
			ProfileUtil::completeProfile($comment);
			$content .= $this->load->view('layout/commententry', $comment, true);
		}
		$json->prevCommentId = $prevCommentId;
		$json->appendHtml = $content;
		$json->serverTimestamp = time();
		echo json_encode($json);
	}
	
	private function notifyTaggedUsers($postid, $text, $isPost = true) {
	
		$tagger = SessionUtil::getUser();
		$pattern = '/@[\w|\.]+/';
        preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
		foreach($matches[0] as $match)
		{
			$userTag = $match[0];
			$user = $this->profile_model->getByEmail(substr($userTag, 1) . '@ntsp.nec.co.jp');
			if (empty($user))
				continue;
			if ($user->userid == $tagger->userid)
				continue;
			
			$notifInfo = new stdClass();
			$notif = new stdClass();
			$notifInfo->userid = $tagger->userid;
			$notifInfo->postid = $postid;
			$notif->for_userid = $user->userid;
			$notif->type = $isPost ? NotifType::TAG_POST : NotifType::TAG_COMMENT;
			$notif->isread = false;
			$notif->info = json_encode($notifInfo);
			$this->notif_model->insert($notif);
		}
	}
}

/* End of file wishlist.php */
/* Location: ./application/controllers/wishlist.php */