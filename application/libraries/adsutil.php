<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdsUtil
{
    static function getRandom() {
        
    //    return array((object)array('title'=>'', 'content'=>'', 'link'=>''));
        $ci = get_instance();
		$ci->load->model('ad_model');
        
       // $ads = $ci->ad_model->getAll();
        $ads = AdsUtil::getRandomChristmas();
        
        $randomIdxList = self::getRandomIndexList(count($ads)-1);
        $finalAds = array();
        
        foreach ($randomIdxList as $idx) {
            $item = $ads[$idx];
            if ($item->content == '$quota') {
                $item->content = self::getNspQuota();
            }
            $finalAds[] = $item;
        }
        return $finalAds;
    }
	
    static function getRandomChristmas() {

        $finalAds = array();
		
		$nspParty = new stdClass();
		$nspParty->title = 'NSP Masquerade Christmas Party';
		$nspParty->content = 'Friday, December 7, 2012 @ Mediterranean, Waterfront Hotel, Lahug Cebu City';
		$finalAds[] = $nspParty;
		
		$nspParty = new stdClass();
		$nspParty->title = 'A Mask is a Must!';
		$nspParty->content = "Be at the party with your best mask. Wear <b>cocktail</b> or <b>formal attire</b>.";
		$finalAds[] = $nspParty;
		
		$nspParty = new stdClass();
		$nspParty->title = 'NSP Kris Kringle';
		$nspParty->content = "Gift amount should be minimum of <b>P300</b>. Meet your secret santa on December 7, 2012!";
		$finalAds[] = $nspParty;
		
		$countdown = new stdClass();
		$countdown->title = 'Countdown to Party';
		$countdown->content = self::getCountdownInfo();
		$finalAds[] = $countdown;
		
		$quoteAd = new stdClass();
		$quoteAd->title = 'Quote of the Moment';
		$quoteAd->content = trim(self::getRandomChristmasQuote());
		$finalAds[] = $quoteAd;

		$maskAd = new stdClass();
		$maskAd->title = 'Buy now!';
		$maskAd->content = '<div><img src="' . AppConfig::$url . AppConfig::getRandomMask() . '" width="150px" /></div>'
		   . 'Masks are available at <b>Hannah\'s</b> or <b>Country Crafts</b> with prices ranging from P30 - P150.';
		$finalAds[] = $maskAd;
		
        return $finalAds;
    }
	
	static function getRandomChristmasQuote() {
	
		$content = file_get_contents(APPPATH . 'models/quotes.txt');
		$quotes = explode("<==>", $content);
		$idx = rand(0, count($quotes) - 1);
		return $quotes[$idx];
	}
	
	
	static function getCountdownInfo() {
	
		$partyTimestamp = strtotime(AppConfig::$nspChristmasPartyDate);
		static $christmasTimestamp = 1356393600;
		static $secondsPerDay = 86400;
		
		$timeNow = strtotime(date("Y-m-d", time()));
		
		$daysLeftTilParty = round(($partyTimestamp - $timeNow) / $secondsPerDay);
		$daysLeftTilChristmas = round(($christmasTimestamp - $timeNow) / $secondsPerDay);
		
		$countdown = array();
		
		$nspPartyText = $daysLeftTilParty
		    . ' Day' . ($daysLeftTilParty != 1 ? 's' : '');
		if ($daysLeftTilParty > 0)
		    $countdown[] = " <b>$nspPartyText</b> until NSP Christmas Party";
		else if ($daysLeftTilParty == 0)
		    $countdown[] = " <b>Today</b> is NSP Christmas Party";
		   
		$xmasPartyText = $daysLeftTilChristmas
		    . ' Day' . ($daysLeftTilChristmas != 1 ? 's' : '');
		if ($daysLeftTilChristmas > 0)
		    $countdown[] = " <b>$xmasPartyText</b> until Christmas Day";
		else if ($daysLeftTilChristmas == 0)
		    $countdown[] = " <b>Today</b> is Christmas Day";
		    
		return implode('<br>', $countdown);
	}
	
    
    private static function getRandomIndexList($maxIdx) {
        
        $idxList = array();
        while (count($idxList) < 5) {
            $idx = rand(0, $maxIdx);
            if (!in_array($idx, $idxList)) {
                $idxList[] = $idx;
            }
        }
        return $idxList;
    }

    static function getNspQuota()
    {
		$quota = SessionUtil::getNspQuota();
		if ($quota == null) {
		    $quota = new stdClass();
		    $quota->last_save = time();
		    $quota->info = self::getNspQuotaHelper();
		    SessionUtil::setNspQuota($quota);
		    return $quota->info;
		}
		$diff = time() - $quota->last_save;
		$fiveMins = 60 * 5;
		if ($diff > $fiveMins) {
		    $quota->last_save = time();
		    $quota->info = self::getNspQuotaHelper();
		    SessionUtil::setNspQuota($quota);
		}
		return $quota->info;
    }
    
    private static function getNspQuotaHelper()
    {
        $me = SessionUtil::getUser();
        //TODO: comment this
        $quotaInfo = Ldap::getInternetQuotaInfo($me->username, base64_decode($me->ldapPassword));
   //     $quotaInfo = DateUtil::dbDate(time()) . ' Sorry you have no more quota.';
        if (empty($quotaInfo)) {
            $quotaInfo = "Can't browse the internet? Check your quota.";
        }
        return $quotaInfo;
    }
}

/* End of file adsutil.php */
/* Location: ./application/libraries/adsutil.php */