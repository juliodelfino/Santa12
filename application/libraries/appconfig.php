<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AppConfig
{
    public static $url = "";
    public static $webName = "";
    public static $separator = "";
    public static $pref = "";
    public static $fromEmail = "";
    public static $nspChristmasPartyDate = "";
	
	//TODO: must be transferred to UI utility class
	public static $imgLoader = "";
	
	function __construct()
	{    	
		if (empty(self::$url))
		{
    		self::$url = get_instance()->config->site_url();
    		self::$pref = 'ss12_';
    		self::$webName = "Secret Santa";
    		self::$imgLoader = '<div class="div-loader">'
    			. '<img src="/components/images/ajax-loader-small.gif" alt="Loading..." />'
    			. '</div>';
    		self::$separator = '·';
    		self::$fromEmail = 'no-reply@ntsp.nec.co.jp';
    		self::$nspChristmasPartyDate = '2012-12-07';
		}
	}
	
	static function getRandomProfilePic() {
	    
	    $ci = get_instance();
	    $path = 'images/santasmiley/';
	    $santaSmileys = FileUtil::getImages($path);
	    $randIdx = rand(0, count($santaSmileys)-1);
	    return $path . $santaSmileys[$randIdx];
	}
	
	static function getRandomMask() {
	    
	    $ci = get_instance();
	    $path = 'images/masks/';
	    $masks = FileUtil::getImages($path);
	    $randIdx = rand(0, count($masks)-1);
	    return $path . $masks[$randIdx];
	}
	
	static function isItSnowTime() {
	    $time = Time::toTime(Time::now());
	    return ($time->minute >= 0 && $time->minute < 5);
	}
}

/* End of file appconfig.php */
/* Location: ./application/libraries/appconfig.php */