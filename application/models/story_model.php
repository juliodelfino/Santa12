<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Story_model extends App_Model {
	const TABLE_NAME = "stories";
	
	function __construct()
    {
        parent::__construct();
    }
	
	function insert($story, $ownerid)
	{
		$postData = new stdClass();
	    $postData->userid = $ownerid;
	    $postData->date_posted = DateUtil::nowDbDate();
	    $postData->date_modified = DateUtil::nowDbDate();
		self::$udb->insert('posts', $postData);
		
		$story->postid = self::$udb->insert_id();
		self::$udb->insert(self::TABLE_NAME, $story);
		return self::$udb->insert_id();
	}
	
	public function update($id, $story)
	{	
		$postData = new stdClass();
		$where = array('postid' => $id);
		self::$udb->update(self::TABLE_NAME, $story, $where);
	    $postData->date_modified = DateUtil::nowDbDate();
		self::$udb->update('posts', $postData, $where);
		
		self::$udb->cache_delete_all();
	}
	
	public function updateDateModified($id) {
	    
		$postData = new stdClass();
		$where = array('postid' => $id);
	    $postData->date_modified = DateUtil::nowDbDate();
		self::$udb->update('posts', $postData, $where);
		
		self::$udb->cache_delete_all();
	}
	
	public function delete($postId)
	{	
		self::$udb->where('postid', $postId);
		self::$udb->delete('likes');
		self::$udb->where('for_postid', $postId);
		self::$udb->delete('comments');
		self::$udb->where('postid', $postId);
		self::$udb->delete('stories');
		self::$udb->where('postid', $postId);
		self::$udb->delete('posts');		
		self::$udb->cache_delete_all();
	}
	
	public function getAll($offset = 0, $limit = 10)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.userid, p.date_posted, p.date_modified, s.*, pr.nickname, pr.photo_file as user_photo "
            . " FROM posts p, stories s, profiles pr WHERE p.postid = s.postid AND pr.userid = p.userid "
            . " ORDER BY p.date_modified DESC LIMIT $offset, $limit";
        $query = self::$udb->query($sql);
        $stories = $query->result();
		foreach ($stories as $story)
		{
			if ($story->user_photo == null) 
				$story->user_photo = AppConfig::getRandomProfilePic();
		}
		return $this->completeUserPhoto($stories);
	}
	
	public function getPrevious($prevFromPostId, $offset = 0, $limit = 10)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.userid, p.date_posted, p.date_modified, s.*, pr.nickname, pr.photo_file as user_photo "
            . " FROM posts p, stories s, profiles pr WHERE p.postid = s.postid AND pr.userid = p.userid "
            . " AND p.date_modified < (select p2.date_modified FROM posts p2 WHERE postid = $prevFromPostId) "
            . " ORDER BY p.date_modified DESC, p.postid DESC LIMIT $offset, $limit";
        $query = self::$udb->query($sql);
        $stories = $query->result();
		foreach ($stories as $story)
		{
			if ($story->user_photo == null) 
				$story->user_photo = AppConfig::getRandomProfilePic();
		}
		return $this->completeUserPhoto($stories);
	}
	
	public function getPreviousByUserId($prevFromPostId, $userId, $offset = 0, $limit = 10)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.userid, p.date_posted, p.date_modified, s.*, pr.nickname, pr.photo_file as user_photo "
            . " FROM posts p, stories s, profiles pr WHERE p.postid = s.postid AND pr.userid = p.userid AND p.userid = $userId "
            . " AND p.date_modified < (select p2.date_modified FROM posts p2 WHERE postid = $prevFromPostId) "
            . " ORDER BY p.date_modified DESC, p.postid DESC LIMIT $offset, $limit";
        $query = self::$udb->query($sql);
        $stories = $query->result();
		foreach ($stories as $story)
		{
			if ($story->user_photo == null) 
				$story->user_photo = AppConfig::getRandomProfilePic();
		}
		return $this->completeUserPhoto($stories);
	}
	
	public function getByUserId($userId, $offset = 0, $limit = 10)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.userid, p.date_posted, p.date_modified, s.*, pr.nickname, pr.photo_file as user_photo "
            . " FROM posts p, stories s, profiles pr WHERE p.postid = s.postid AND pr.userid = p.userid AND p.userid = $userId "
            . " ORDER BY p.date_modified DESC LIMIT $offset, $limit";
        $query = self::$udb->query($sql);
        $stories = $query->result();
		return $this->completeUserPhoto($stories);
	}
	
	public function get($postid)
	{
		self::$udb->cache_on();
		
        $sql = 'SELECT p.userid, p.date_posted, p.date_modified, s.*, pr.nickname, pr.photo_file as user_photo '
            . ' FROM posts p, stories s, profiles pr '
			. " WHERE p.postid = s.postid AND pr.userid = p.userid AND p.postid = $postid";
        $query = self::$udb->query($sql);
        $story = $query->row();
        if ($story != null && $story->user_photo == null) {
			$story->user_photo = AppConfig::getRandomProfilePic();
        }
        return $story;
	}
	
	public function userHasStories($userId)
	{
        $sql = "SELECT count(p.postid) cnt FROM posts p, stories s WHERE p.postid = s.postid AND p.userid = $userId ";
        $query = self::$udb->query($sql);
        $result = $query->row();
		return $result->cnt > 0;
	}
	
	private function completeUserPhoto($stories)
	{
		foreach ($stories as $story)
		{
			if ($story->user_photo == null) 
				$story->user_photo = AppConfig::getRandomProfilePic();
		}
		return $stories;
	}
}

/* End of file story_model.php */
/* Location: ./application/models/story_model.php */