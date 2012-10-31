<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SessionUtil
{
	static $sessionStarted = false;
	
	static function trySessionStart()
	{
		if (!SessionUtil::$sessionStarted)
		{
			session_start();
			SessionUtil::$sessionStarted = true;
		}
	}
	
	static function get($key)
	{
		//Initially, CodeIgniter's session handling was used but we 
		//encountered issues such as unexpected loss of session data, like
		//reserving a class schedule; this is okay in student account, but
		//not okay in admin account (thru impersonation).
		//see this; http://stackoverflow.com/questions/2449118/codeigniter-session-data-not-available-in-other-pages-after-login
		//Therefore we are reverting to the usage of native PHP Session,
		//which works well.
		$keyWithPrefix = AppConfig::$pref . $key;
		return !empty($_SESSION[$keyWithPrefix]) ? $_SESSION[$keyWithPrefix] : null;
	}
	
	static function id()
	{
		//$ci = get_instance();
		//return $ci->session->userdata('session_id');
		return session_id();
	}

	static function visitorIpAddress()
	{
		//return $ci->session->userdata('ip_address');
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		return $_SERVER["REMOTE_ADDR"];
	}
	
    static function getUser() 
    {
    	SessionUtil::trySessionStart();
    	$user = SessionUtil::get('user');
    	if ($user == null)
    	{
    		$user = (object)array('userid' => -1, 'email' => '', 'role' => Role::ANONYMOUS);
    	}
		return $user;
    }
    
    static function setAuthenticatedUser($user)
    {
    	SessionUtil::trySessionStart();
		unset($user->password);
		$_SESSION[AppConfig::$pref . 'logged_in'] = true;
		$_SESSION[AppConfig::$pref . 'user'] = $user; 
    }
    
    static function getProfile()
    {
    	SessionUtil::trySessionStart();
    	return SessionUtil::get('profile');
    }
    
    static function setProfile($newProfile)
    {
    	SessionUtil::trySessionStart();
    	$_SESSION[AppConfig::$pref . 'profile'] = $newProfile;
    }
    
	const PhilippineTimezone = 8;
    //gets the user timezone offset in seconds
    static function getTimezoneOffset()
	{
        	//return self::loggedIn() ? (SessionUtil::PhilippineTimezone * 3600) : 0;
        	return SessionUtil::PhilippineTimezone * 3600;
    }
    
    static function loggedIn() 
    {
    	SessionUtil::trySessionStart();
		return SessionUtil::get('logged_in');
    }
    
    static function setLastVisitedPage($link)
    {
    	SessionUtil::trySessionStart();
    	$_SESSION[AppConfig::$pref . 'lastVisitedPage'] = $link;
    }
    
    static function getLastVisitedPage()
    {
    	SessionUtil::trySessionStart();
    	$link = SessionUtil::get('lastVisitedPage');
    	$_SESSION[AppConfig::$pref . 'lastVisitedPage'] = null;
    	return $link;
    }
    
    static function setLanguage($language)
    {
    	SessionUtil::trySessionStart();
    	$_SESSION[AppConfig::$pref . 'language'] = $language;
    }
    
    static function getLanguage()
    {
    	SessionUtil::trySessionStart();
    	$language = SessionUtil::get('language');
    	if ($language == null) 
    	{
    		$language = 'english';
    	}
    	return $language;
    }
    
    static function getNspQuota()
    {
    	SessionUtil::trySessionStart();
		return SessionUtil::get('nsp_quota');
    }
    
    static function setNspQuota($quota)
    {
    	SessionUtil::trySessionStart();
    	$_SESSION[AppConfig::$pref . 'nsp_quota'] = $quota;
    }
    
    static function getFriendsIdList()
    {
    	SessionUtil::trySessionStart();
		return SessionUtil::get('friends_list');
    }
    
    static function setFriendsIdList($friendsList)
    {
    	SessionUtil::trySessionStart();
    	$_SESSION[AppConfig::$pref . 'friends_list'] = $friendsList;
    }
    
    static function destroy()
    {
		$email = self::getUser()->email;
		
		//source: http://stackoverflow.com/questions/508959/truly-destroying-a-php-session
    	SessionUtil::trySessionStart();
		$_SESSION = array();
		if (isset($_COOKIES[session_name()])) { 
            $params = session_get_cookie_params();
            setcookie(session_name(), '', 1, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
        }
		session_destroy();
        session_start();
        if (!isset($_SESSION['CREATED'])) {
            // invalidate old session data and ID
            session_regenerate_id(true);
            $_SESSION['CREATED'] = time();
        }
		
		$user = new stdClass();
		$user->userid = -1;
		$user->email = $email;
		$user->role = Role::ANONYMOUS;
		$_SESSION[AppConfig::$pref . 'logged_in'] = null;
		$_SESSION[AppConfig::$pref . 'user'] = $user;
    }
}

/* End of file sessionutil.php */
/* Location: ./application/libraries/sessionutil.php */