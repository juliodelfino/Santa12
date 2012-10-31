<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role {

	const ANONYMOUS = 0;
	const ADMIN = 1;
	const USER = 2;
	
	static function getList()
	{
		return array(
			Role::ADMIN => "Administrator",
			Role::TEACHER => "User"
		);
	}
	
	private static $roleList = null;
	static function getValue($type)
	{
		if (self::$roleList == null)
			self::$roleList = self::getList();
		return self::$roleList[$type];
	}
}

/* End of file role.php */
/* Location: ./application/libraries/enum/role.php */