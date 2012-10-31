<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Time {

    public $month;
    public $day;
    public $year;
    public $hour;
    public $minute;
    public $second;
    public $pgTime;

    static function toTime($pgTime) {

        $dateTime = explode(" ", $pgTime);
        $timeStamp = new Time();
        $dateComponents = explode("-", $dateTime[0]);
        $timeStamp->year = $dateComponents[0];
        $timeStamp->month = $dateComponents[1];
        $timeStamp->day = $dateComponents[2];

        if (!isset($dateTime[1]))
            return $timeStamp;
        $timeComponents = explode(":", $dateTime[1]);
        $timeStamp->hour = $timeComponents[0];
        $timeStamp->minute = $timeComponents[1];
        $timeStamp->second = $timeComponents[2];
        $timeStamp->pgTime = $pgTime;
        return $timeStamp;
    }
    
    static function now() {

        return gmdate ("Y-m-d H:i:s", time());
    }

    static function toTimeRelativeNow($pgTimestamp) {

        $dateNow = Time::toTime(DateUtil::dbDate(DateUtil::localTime()));
        $timeStamp = Time::toTime($pgTimestamp);
        $strTime = "";

        $secondsElapsed = strtotime($dateNow->pgTime) - strtotime($timeStamp->pgTime);
        $hoursElapsed = intval($secondsElapsed / ( 60 * 60));
   //     $daysElapsed = intval($secondsElapsed / (60 * 60 * 24));


		if ($secondsElapsed < 60)  {
			if ($secondsElapsed == 1) $secondsElapsed = 0;
			$strTime = $secondsElapsed . " seconds ago";
		}
        else if (($secondsElapsed/60) < 60) {
            $diff = intval($secondsElapsed/60);
            $strTime = ($diff > 1 ? $diff . " minutes" : "a minute") . " ago";
        }
        else if ($hoursElapsed <= $dateNow->hour) {
            $diff = $dateNow->hour - $timeStamp->hour;
            $strTime = ($diff > 1 ? $diff . " hours" : "an hour") . " ago";
        }

        else if ($hoursElapsed - $dateNow->hour <= 24) {
            $strTime = "Yesterday " . strftime("%I:%M %p", strtotime($timeStamp->pgTime));
        }
        else {
        	if ($dateNow->year == $timeStamp->year)
            	$strTime = strftime("%B %d at %I:%M %p", strtotime($timeStamp->pgTime));
            else
            	$strTime = strftime("%B %d, %Y at %I:%M %p", strtotime($timeStamp->pgTime));
        }
        return $strTime;
    }
    
    static function lessThanADay($pgTimestamp)
    {
        $dateNow = Time::toTime(DateUtil::dbDate(DateUtil::localTime()));
        $timeStamp = Time::toTime($pgTimestamp);

        $secondsElapsed = strtotime($dateNow->pgTime) - strtotime($timeStamp->pgTime);
        $hoursElapsed = intval($secondsElapsed / ( 60 * 60));
        
        return ($hoursElapsed <= $dateNow->hour);
    }
}

/* End of file time.php */
/* Location: ./application/models/time.php */