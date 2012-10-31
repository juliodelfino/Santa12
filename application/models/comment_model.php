<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends App_Model {
	const TABLE_NAME = "comments";
	
	function __construct()
    {
        parent::__construct();
    }
	
	function insert($comment, $ownerid)
	{
		$postData = new stdClass();
	    $postData->userid = $ownerid;
	    $postData->date_posted = DateUtil::nowDbDate();
	    $postData->date_modified = DateUtil::nowDbDate();
		self::$udb->insert('posts', $postData);
		
		$comment->postid = self::$udb->insert_id();
		self::$udb->insert(self::TABLE_NAME, $comment);
		return self::$udb->insert_id();
	}
	
	public function get($postid)
	{
		self::$udb->cache_on();
		
        $sql = 'SELECT p.userid, p.date_posted, p.date_modified, c.* FROM posts p, comments c '
			. " WHERE p.postid = c.postid AND c.postid = $postid";
        $query = self::$udb->query($sql);
        return $query->row();
	}
	
	function getForPostId($postid, $currentUserId, $afterCommentId = 0)
	{
		self::$udb->cache_on();
		
		$sql = "SELECT c.*, count(l.postid) AS likecount, l2.userid = $currentUserId as iLike, p.date_posted, p.date_modified, p.userid, pr.nickname, pr.photo_file "
            . ' FROM comments c LEFT JOIN likes l ON l.postid = c.postid '
			. " LEFT JOIN likes l2 ON l2.postid = c.postid AND l2.userid = $currentUserId "
            . ' JOIN posts p ON p.postid = c.postid '
            . ' JOIN profiles pr ON pr.userid = p.userid '
            . " WHERE c.for_postid = $postid AND c.postid > $afterCommentId "
            . ' GROUP BY c.postid;';
		
        $query = self::$udb->query($sql);
        $comments = $query->result();
		foreach ($comments as $comment)
		{
			if ($comment->photo_file == null) 
				$comment->photo_file = AppConfig::getRandomProfilePic();
		}
		return $comments;
	}
	
	public function delete($postId)
	{	
		self::$udb->where('postid', $postId);
		self::$udb->delete('likes');
		self::$udb->where('postid', $postId);
		self::$udb->delete('comments');
		self::$udb->where('postid', $postId);
		self::$udb->delete('posts');		
		self::$udb->cache_delete_all();
	}
}

/* End of file comment_model.php */
/* Location: ./application/models/comment_model.php */