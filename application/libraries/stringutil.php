<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StringUtil
{
    static function contains($needle, $haystack)
    {
        return (strpos($haystack, $needle) !== false);
    }
    
    static function replace($pattern, $replacement, $subject)
    {
        if (self::contains('@', $subject))
        {
            $result = preg_replace($pattern, $replacement, $subject);
        }
        else {
            $result = $subject;
        }
        return $result;
    }
	
	static function cleanupChars($text)
	{
		return str_replace(array("\r"), '', $text); 
	}
	
	static function generateUid()
	{
		return sprintf("%08x%08x%08x%08x", mt_rand(), mt_rand(), mt_rand(), mt_rand());
		//in CodeIgniter's Session.php, they use this: md5(uniqid($sessid, TRUE))
	}
	
	static function getOrdinal($number) { 
	
	// when fed a number, adds the English ordinal suffix. Works for any 
	// number, even negatives 
	
		if ($number % 100 > 10 && $number %100 < 14): 
			$suffix = "th"; 
		else: 
			switch($number % 10)
			{
				case 1: 
					$suffix = "st"; 
					break; 
				case 2: 
					$suffix = "nd"; 
					break; 
				case 3: 
					$suffix = "rd"; 
					break; 
				default: 
					$suffix = "th"; 
					break; 
			} 
		endif; 
		return "${number}<SUP>$suffix</SUP>"; 
	}
	
    static function filterContent($text) {
    
    //    $text = str_replace("'", "\'", $text);
    //    $text = str_replace("\\'", "\'", $text);
        $text = preg_replace("/<.*?>/s", "", $text);
        $text = trim($text);
        //TODO: replace unallowable characters (e.g. single quote [' to \'])
        return $text;
    }
    
    static function formatContent($text) {
           
        //1st attempt to format URLs
        //   $text = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', 
        //   	'<a href="$1" target="_blank">$1</a>', $text);
	 
        //2nd attempt to format URLs
        //   $text = preg_replace("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", 
        //   	"'<a href=\"$1\" target=\"_blank\">$1</a>$4'", $text);
	 
        //3rd attempt to format URLs
        $text = preg_replace_callback('#((http|https|ftp)://[^\s]+)#i',
                  create_function ('$matches', 'return "<a href=\'" . $matches[0] . "\' target=\'_blank\'>" . $matches[0] . "</a>";'), $text);
			
        $text = nl2br($text);    
        return $text;
    }
}

//added function to echo line before returning it from calling CI's lang() function.
if ( ! function_exists('elang'))
{
	function elang($line, $id = '')
	{
		$message = vlang($line, $id);
		echo $message;
		return $message;
	}
}

//added function to verify line returning it from calling CI's lang() function.
if ( ! function_exists('vlang'))
{
	function vlang($line, $id = '')
	{
		$message = lang($line, $id);
		if ($message == null)
			throw new Exception("Keyword '$line' not found in " . SessionUtil::getLanguage() . " language property files.");
		return $message;
	}
}

//added function to verify line returning it from calling CI's lang() function.
if ( ! function_exists('langdate'))
{
	function langdate($format, $timestamp)
	{
		
		if (SessionUtil::getLanguage() == "japanese")
		{
			$days = array('日','月','火','水','木','金','土');
			$format = str_replace("F j, Y", "Y年n月j日", $format);
			$format = str_replace("l", $days[gmdate("w", $timestamp)], $format);
			$format = str_replace("F j", "n月j日", $format);
			$format = str_replace("h:i A", "H:i", $format);
			$format = str_replace("g:i A", "H:i", $format);
		}
		return gmdate($format, $timestamp);
	}
}

if ( ! function_exists('html_bold'))
{
	function html_b($text)
	{
		return '<b>' . $text . '</b>';
	}
}

if ( ! function_exists('br2nl'))
{
	function br2nl($string)
	{
		return preg_replace('#<br\s*?/?>#i', "\n", $string); 
	//	return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	} 
}

if ( ! function_exists('removeBr'))
{
	function removeBr($string)
	{
		return preg_replace('#<br\s*?/?>#i', '', $string); 
	//	return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	} 
}

/* End of file stringutil.php */
/* Location: ./application/libraries/stringutil.php */