<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
     
class Ldap {

	static function isValidLdapUser($username, $password)
	{
		$data = array(
	      'form_loginbutton.x' => '0',
	      'form_loginbutton.y' => '0',
	      'form_password' => $password,
	      'form_uid' => $username    
		);
		list($header, $content) = HttpRequest::request(
			"http://www/ldap/index.php",
			"http://www/ldap/index.php",
			$data, 'POST'
		);
		$loginFailed = stripos($content, 'invalid');
		$isLoginPage = stripos($content, 'form_loginbutton');
		return !$loginFailed && !$isLoginPage;
	}
	
	static function getLdapUserInfo($username)
	{
		$data = array(
	      'form_submitbutton.x' => '0',
	      'form_submitbutton.y' => '0',
	      'form_uid' => $username,
          'form_submitbutton' => 'CHANGE+PHOTO'		  
		);
		list($header, $content) = HttpRequest::request(
			"http://www/ldap/pubsrch/listfilter.php",
			"http://www/ldap/pubsrch/listfilter.php",
			$data, 'POST'
		);
		
		$data = array(
	      'form_submitbutton.x' => '0',
	      'form_submitbutton.y' => '0',
	      'form_uid' => $username    
		);
		//Check the content of Request() method if it has included the Cookie: PHPSESSID
		//to make the retrieval of user info working
		list($header, $content) = HttpRequest::request(
			"http://www/ldap/pubsrch/listentry.php"
		);
		
		preg_match_all('/hidden.*\/>/', $content, $matches);
		
		$userdata = array();
		foreach($matches[0] as $match) {
			preg_match_all('/(?<=name=").*?(?=")/', $match, $nameValue);
			preg_match_all('/(?<=value=").*?(?=")/', $match, $valValue);
			$userdata[$nameValue[0][0]] = $valValue[0][0];
		}
		$userinfo = new stdClass();
		$userinfo->username = $userdata["form_uid"];
		$userinfo->encryptedPassword = $userdata["form_userpassword"];
		$userinfo->fullname = $userdata["form_cn"];
		$userinfo->lastname = $userdata["form_sn"];
		$userinfo->email = $userdata["form_mail"];
		$userinfo->employeeId = $userdata["form_employeenumber"];
		$userinfo->local = $userdata["form_telephonenumber"];
		$userinfo->position = $userdata["form_title"];

		return $userinfo;
	}
	
	static function getInternetQuotaInfo($username, $password)
	{
		$data = array(
	      'form_loginbutton.x' => '0',
	      'form_loginbutton.y' => '0',
	      'form_password' => $password,
	      'form_uid' => $username    
		);
		list($header, $content) = HttpRequest::request(
			"http://www/quota/index.php",
			"http://www/quota/index.php",
			$data, 'POST'
		);
		if (preg_match('/[^>]*hrs./', $content, $regs))
			return $regs[0];
		else return null;
	}
}

/* End of file ldap.php */
/* Location: ./application/libraries/ldap.php */