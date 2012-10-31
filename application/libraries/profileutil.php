<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProfileUtil
{
	static function completeProfile($profile)
	{
		if (!is_array($profile)) 
		{
			ProfileUtil::completeProfileHelper($profile);
		}
		else
		{
			foreach($profile as $item)
			{
				ProfileUtil::completeProfileHelper($item);
			}
		}
	}
	
	private static function completeProfileHelper($profile)
	{
	    if (empty($profile->photo_file)) {
	        $profile->photo_file = AppConfig::getRandomProfilePic();
	    }
	
	}
}

/* End of file profileutil.php */
/* Location: ./application/libraries/profileutil.php */