<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends App_Model {
    
	const TABLE_NAME = "profiles";
	const QUERY_LIMIT = 20;
	
	function __construct()
    {
        parent::__construct();
    }
	
	public function getAll()
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.*, u.email FROM users u, profiles p WHERE u.userid = p.userid ORDER BY nickname";
        $query = self::$udb->query($sql);
        return $query->result();
	}
	
	public function getRandom($offset = 0, $limit = self::QUERY_LIMIT)
	{
		self::$udb->cache_on();
		
		$friendsIdList = SessionUtil::getFriendsIdList();
		$size = count($friendsIdList);
     
	    if ($size == 0 || $offset == 0) {
		
	        $sql = 'SELECT userid FROM profiles';
            $idList = self::$udb->query($sql)->result();
            $friendsIdList = array();
            foreach ($idList as $item) {
                $friendsIdList[] = $item->userid;
            }
            $friendsIdList = self::shuffleList($friendsIdList);
	        SessionUtil::setFriendsIdList($friendsIdList);
	    }
	    $result = self::extract($friendsIdList, $offset);
	    $finalIds = implode(',', $result);

	    if (empty($finalIds)) {
	        $finalIds = 0;
	    }	    
        $sql = "SELECT p.*, u.email FROM users u, profiles p WHERE u.userid = p.userid AND u.userid in ($finalIds) ORDER BY RAND()";
        $query = self::$udb->query($sql);
        return $query->result();
	}
	
	static function shuffleList($list)
	{
	    $size = count($list);
	    for ($i = 0; $i < $size/2; $i++)
	    {
	        $newIdx = rand(0, $size-1);
	        $item = $list[$newIdx];
	        $list[$newIdx] = $list[$i];
	        $list[$i] = $item;
	    }
	    return $list;
	}
	
	static function extract($fromList, $offset)
	{
	    $limit = self::QUERY_LIMIT;
	    if ($limit > (count($fromList) - $offset))
	    {
	        $limit = count($fromList) - $offset;
	    }
	    return array_slice($fromList, $offset, $limit);
	}
	
	public function get($userid)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.*, u.email FROM users u, profiles p WHERE u.userid = p.userid AND u.userid = $userid";
        $query = self::$udb->query($sql);
        return $query->row();
	}
	
	public function getByEmail($email)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.*, u.email FROM users u, profiles p WHERE u.userid = p.userid AND u.email = '$email'";
        $query = self::$udb->query($sql);
        return $query->row();
	}
	
	public function getByGifteeEmail($email)
	{
		self::$udb->cache_on();
		
        $sql = "SELECT p.*, u.email FROM users u, profiles p WHERE u.userid = p.userid AND p.giftee_email = '$email'";
        $query = self::$udb->query($sql);
        return $query->row();
	}
	
	public function insert($profile)
	{
		self::$udb->insert(self::TABLE_NAME, $profile);
		return $profile->userid;
	}
	
	public function update($id, $profile)
	{	
		self::$udb->update(self::TABLE_NAME, $profile, array('userid' => $id));
		self::$udb->cache_delete_all();
	}
	
	function search($text)
	{
        $sql = "SELECT p.*, u.email FROM profiles p, users u "
			. " WHERE p.userid = u.userid AND (`nickname` 
			REGEXP '.*$text.*'
			OR `fullname` 
			REGEXP '.*$text.*'
			OR `email` 
			REGEXP '.*$text.*')";
        $query = self::$udb->query($sql);
        return $query->result();
	}
}

/* End of file profile_model.php */
/* Location: ./application/models/profile_model.php */