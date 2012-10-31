<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notif_model extends App_Model {
	const TABLE_NAME = "notifs";
	
	function __construct()
    {
        parent::__construct();
    }
	
	function insert($notif)
	{
		if ($this->exists($notif))
			return null;
		$notif->date = DateUtil::nowDbDate();
		self::$udb->insert(self::TABLE_NAME, $notif);
		return self::$udb->insert_id();
	}
	
	function update($notif, $notifid)
	{
		$where = array('notifid' => $notifid);
		self::$udb->update(self::TABLE_NAME, $notif, $where);
	}
	
	function getUnreadCount($userid)
	{
		$sql = "SELECT count(notifid) AS cnt FROM notifs WHERE isread = false AND for_userid = $userid";
        $query = self::$udb->query($sql);
        return $query->row()->cnt;
	}
	
	function getUnreadForUserId($userid)
	{
		$sql = "SELECT * FROM notifs WHERE isread = false AND for_userid = $userid ORDER BY date DESC";
		
        $query = self::$udb->query($sql);
        $notifs = $query->result();
		return $notifs;
	}
	
	function setAsRead($notifs) {
	    
	    $notifIdList = array();
	    if (count($notifs) == 0) {
	        return $notifIdList;
	    }
	    foreach ($notifs as $notif) {
	        $notifIdList[] = $notif->notifid;
	    }
	    $notifIds = implode(',', $notifIdList);
	    
        $sql = "UPDATE notifs SET isread = true WHERE notifid in ($notifIds)";
        self::$udb->query($sql);
	}
	
	function getForUserId($userid, $offset=0, $limit=7)
	{
		$sql = "SELECT * FROM notifs WHERE for_userid = $userid ORDER BY date DESC LIMIT $offset, $limit";
		
        $query = self::$udb->query($sql);
        $notifs = $query->result();        
		return $notifs;
	}
	
	function exists($notif)
	{
		if ($notif->type == NotifType::SENT_REMINDER)
			return false;
		$info = json_decode($notif->info);
		if (empty($info))
			$info = (object)array('type' => NotifType::NEW_POST, 'postid' => 0, 'userid' => 0);		
		$postid = ($notif->type == NotifType::LIKE_ON_COMMENT ? $info->storyid : $info->postid);

		$sql = "SELECT * FROM notifs where info REGEXP CONCAT('userid.*[^0-9]', '" . $info->userid 
			. "', '[^0-9].*', '(postid|storyid).*[^0-9]', '" . $postid . "', '[^0-9]+') AND for_userid = $notif->for_userid AND type = $notif->type";
		$query = self::$udb->query($sql);
		return $query->row() != null;
	}
	
	function deleteHavingPostid($postid, $notifType = 0)
	{
		$sql = "DELETE FROM notifs where info REGEXP CONCAT('(postid|storyid).*[^0-9]" . $postid . "[^0-9]+')";
		if ($notifType > 0) {
		    $sql .= " AND type = $notifType ";
		}
        $query = self::$udb->query($sql);
	}
}

/* End of file notif_model.php */
/* Location: ./application/models/notif_model.php */