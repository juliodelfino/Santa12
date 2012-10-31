<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Like_model extends App_Model {
	const TABLE_NAME = "likes";
	
	function __construct()
    {
        parent::__construct();
    }
	
	function insert($likeEntry)
	{
	    $likeEntry->date_liked = DateUtil::nowDbDate();
		self::$udb->insert(self::TABLE_NAME, $likeEntry);
		return self::$udb->insert_id();
	}
	
	function toggleLike($likeEntry)
	{
		$toggleVal = true;
		$sql = "SELECT * FROM likes WHERE userid = $likeEntry->userid AND postid = $likeEntry->postid";
		$query = self::$udb->query($sql);
        if ($query->result() == null) {
			$this->insert($likeEntry);
		}
		else {
			$sql = "DELETE FROM likes WHERE userid = $likeEntry->userid AND postid = $likeEntry->postid";
			self::$udb->query($sql);
			$toggleVal = false;
		}
		return $toggleVal;
	}
	
	function getByPostId($postid)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.userid, p.nickname FROM likes l, profiles p WHERE l.userid = p.userid AND l.postid = $postid";
        $query = self::$udb->query($sql);
        return $query->result();
	}
	
	function getByPostIdMore($postid)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.userid, p.nickname, p.photo_file FROM likes l, profiles p WHERE l.userid = p.userid AND l.postid = $postid ORDER BY p.nickname";
        $query = self::$udb->query($sql);
        return $query->result();
	}
}

/* End of file like_model.php */
/* Location: ./application/models/like_model.php */