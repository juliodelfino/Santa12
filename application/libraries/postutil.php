<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PostUtil
{
    static function likesToHtml($users) {
        		
        $cnt = count($users);
        if ($cnt == 0) return '';
        
        $me = SessionUtil::getUser();
        $myIndex = -1;
        $meIncluded = false;
        for ($i=0; $i < $cnt; $i++) {
            
            if ($users[$i]->userid == $me->userid) {
                $myIndex = $i;
                $meIncluded = true;
                break;
            }
        }
        
        if ($meIncluded) {
            array_splice($users, $myIndex, 1);
        }
        
        $maxNameDisplay = $meIncluded ? 2 : 3;
        
        if ($cnt == 1) {
            if ($meIncluded)
                return 'You like this.';
            else return self::toLinkedName($users[0]) . ' likes this.';
        }
        
        $cnt = $meIncluded ? $cnt-1 : $cnt;
        $detail = $meIncluded ? 'You,' : '';
        if ($cnt <= $maxNameDisplay) {
            for ($i=0; $i<$cnt-1; $i++) {

                $detail .= ' ' . self::toLinkedName($users[$i]) . ',';
            }
            $detail = substr($detail, 0, strlen($detail)-1);
            $detail .= ' and ' . self::toLinkedName($users[$cnt-1]) . ' like this.';
            return $detail;
        }
        for($i=0; $i < $maxNameDisplay-1; $i++) {
            $detail .= ' ' . self::toLinkedName($users[$i]) . ',';
        }
        $detail = substr($detail, 0, strlen($detail)-1);
        $otherUsers = $cnt - ($maxNameDisplay-1);
        $detail .= ' and <a href="javascript:void(0)" class="like-view">' . $otherUsers . ' others</a> like this.';
        return $detail;
    }
    
    private static function toLinkedName($user)
    {
        return '<a href="' . AppConfig::$url . 'profile?id=' . $user->userid
            . '">' . $user->nickname . '</a>';
    }

    static function tryHideLongText($text)
    {
        $limit = 300;
        if (strlen($text) < $limit) {
            $text = StringUtil::formatContent($text);
        }
		else {
			$pattern = '/\s/';
			preg_match($pattern, substr($text, $limit), $matches, PREG_OFFSET_CAPTURE);
			if (empty($matches)) {
				$text = StringUtil::formatContent($text);
			}
			else {
				$limit = $limit +  $matches[0][1];
				
				$substring = substr($text, 0, $limit);
				$toHide = substr($text, $limit);
				$text = '<span class="main-text">' . StringUtil::formatContent($substring) 
					. '</span><span class="dot3">...</span>'
					. '<span class="hidden-text" style="display: none">' 
					. StringUtil::formatContent($toHide) . '</span> <a class="show-hidden-text" href="javascript:void(0)">See More</a>';
			}
		}
		$text = self::replaceUserTags($text);
        return $text;
    }
	
    static function replaceUserTags($text) {
	
		$ci = get_instance();
		$ci->load->model('profile_model');
		$pattern = '/@[\w|\.]+/';
        preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
		foreach($matches[0] as $match)
		{
			$userTag = $match[0];
			$user = $ci->profile_model->getByEmail(substr($userTag, 1) . '@ntsp.nec.co.jp');
			if (empty($user))
				continue;
			$text = str_replace($userTag, '<a href="' . AppConfig::$url . 'profile?id=' . $user->userid . '">' 
			    . $user->nickname . '</a>', $text);
		}
	  return $text;
    }
    
    static function dbTimeToHtml($dbTime)
    {
        echo '<span' . (Time::lessThanADay($dbTime) ?  
			            (' ts="' . strtotime($dbTime) . '" class="live-ts">') : ' class="ts">')
			            . Time::toTimeRelativeNow($dbTime) . '</span>';
    }
}

/* End of file postutil.php */
/* Location: ./application/libraries/postutil.php */