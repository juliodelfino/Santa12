<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DateUtil
{	
	//converts a date from user-readable format "F j, Y h:i A" to database format "Y-m-d H:i:00"
	//example: April 18, 2011 8:22 AM   => 2011-04-18 08:22:00
	static function fromUserDateTimeToDbDateTime($userDateTime)
	{
		$tmpDate = date_parse_from_format("F j, Y h:i A", $userDateTime);
		return $tmpDate['year'] . "-" . $tmpDate['month'] . "-" . $tmpDate['day'] . " "
			. $tmpDate['hour'] . ":" . $tmpDate['minute'] . ":00";
	}
	
	static function dbDate($timestamp)
	{
		return gmdate("Y-m-d H:i:s", $timestamp);
	}
	
	static function nowDbDate()
	{
		return gmdate("Y-m-d H:i:s", self::localTime());
	}
	
	static function noSeconds($timestamp)
	{
		return strtotime(gmdate("Y-m-d H:i:00", $timestamp));
	}
	
	//gets the user's local time
	static function localTime()
	{
//		date_default_timezone_set("Asia/Hong_Kong"); 
		return time() + SessionUtil::getTimezoneOffset();
	}
	
	//converts UTC time to user's timestamp
	static function utcToUserTime($utcTime)
	{
		return $utcTime  + SessionUtil::getTimezoneOffset();
	}
	
	//converts user's timestamp to UTC time
	static function userToUtcTime($userTime)
	{
		return $userTime - SessionUtil::getTimezoneOffset();
	}
	
	static function toUserFriendlyDate($timestamp)
	{
	//	$timestamp = self::utcToUserTime($timestamp);
		$secsPerDay = 60 * 60 * 24;
		$localTime = DateUtil::localTime();
		$today = strtotime(gmdate("Y-m-d", $localTime));
		$timediff = $timestamp - $today;
		$strDate = langdate("F j, Y", $timestamp);
		
		if ($timediff < $secsPerDay)
			return $strDate . " (" . vlang('common.today') . ")";
		else if ($timediff >= $secsPerDay && $timediff < $secsPerDay * 2)
			return $strDate . " (" . vlang('common.tomorrow') . ")";
		else return $strDate;
		
	}
	
	static function toUserDateTime($timestamp)
	{
		return langdate("F j, Y, g:i A", self::utcToUserTime($timestamp));
	}
	
	static function diff($date1, $date2)
	{
		$today = strtotime($date2);
		$myBirthDate = strtotime($date1);
		return round(abs($today-$myBirthDate)/60/60/24);
	}
	
	static function getTimezone($country)
	{
		$countryTzMap = self::getCountryTimezoneMap();
		$country = self::tryGetValidCountry($country);
		return $country != null? $countryTzMap[$country] : 0;
	}

	private static $countryTzMap;

	static function getCountryTimezoneMap()
	{
		if (self::$countryTzMap == null)
		{
			self::$countryTzMap = array(
				"Afghanistan" => 4.5,
				"Albania" => 1,
				"Algeria" => 1,
				"American Samoa" => -11,
				"Andorra" => 1,
				"Angola" => 1,
				"Anguilla" => -4,
				"Antarctica" => -2,
				"Antigua & Barbuda" => -4,
				"Argentina" => -3,
				"Argentina western prov" => -4,
				"Armenia" => 4,
				"Aruba" => -4,
				"Ascension" => 0,
				"Australia Northern Territory" => 9.5,
				"Australia Lord Howe Island" => 10.5,
				"Australia New South Wales" => 10,
				"Australia Queensland" => 10,
				"Australia Victoria" => 10,
				"Australia Australian Captial Territory" => 10,
				"Australia South" => 9.5,
				"Australia Tasmania" => 10,
				"Australia Western" => 8,
				"Austria" => 1,
				"Azerbajian" => 3,
				"Azores" => -1,
				"Bahamas" => -5,
				"Bahrain" => 3,
				"Balearic Islands" => 1,
				"Bangladesh" => 6,
				"Barbados" => -4,
				"Belarus" => 2,
				"Belgium" => 1,
				"Belize" => -6,
				"Benin" => 1,
				"Bermuda" => -4,
				"Bhutan" => 6,
				"Bolivia" => -4,
				"Bonaire" => -4,
				"Bosnia Hercegovina" => 1,
				"Botswana" => 2,
				"Brazil Acre" => -4,
				"Brazil Atlantic Islands" => -1,
				"Brazil East" => -3,
				"Brazil West" => -4,
				"British Virgin Islands" => -4,
				"Brunei" => 8,
				"Bulgaria" => 2,
				"Burkina Faso" => 0,
				"Burundi" => 2,
				"Cambodia" => 7,
				"Cameroon" => 1,
				"Canada Central" => -6,
				"Canada Eastern" => -5,
				"Canada Mountain" => -7,
				"Canada Yukon & Pacific" => -8,
				"Canada Atlantic" => -4,
				"Canada Newfoundland" => -3.5,
				"Canary Islands" => 0,
				"Canton Enderbury Islands" => -11,
				"Cape Verde" => -1,
				"Caroline Island" => 11,
				"Cayman Islands" => -5,
				"Central African Rep" => 1,
				"Chad" => 1,
				"Channel Islands" => 0,
				"Chatham Island" => 12.75,
				"Chile" => -4,
				"China People's Rep" => 8,
				"Christmas Islands" => -10,
				"Colombia" => -5,
				"Congo" => 1,
				"Cook Islands" => -10,
				"Costa Rica" => -6,
				"Cote d'Ivoire" => 0,
				"Croatia" => 1,
				"Cuba" => -5,
				"Curacao" => -4,
				"Cyprus" => 2,
				"Czech Republic" => 1,
				"Dahomey" => 1,
				"Denmark" => 1,
				"Djibouti" => 3,
				"Dominica" => -4,
				"Dominican Republic" => -4,
				"Easter Island" => -6,
				"Ecuador" => -5,
				"Egypt" => 2,
				"El Salvador" => -6,
				"England" => 0,
				"Equitorial Guinea" => 1,
				"Eritrea" => 3,
				"Estonia" => 2,
				"Ethiopia" => 3,
				"Falkland Islands" => -4,
				"Faroe Island" => 0,
				"Fiji" => 12,
				"Finland" => 2,
				"France" => 1,
				"French Guiana" => -3,
				"French Polynesia" => -10,
				"Gabon" => 1,
				"Galapagos Islands" => -5,
				"Gambia" => 0,
				"Gambier Island" => -9,
				"Georgia" => 4,
				"Germany" => 1,
				"Ghana" => 0,
				"Gibraltar" => 1,
				"Greece" => 2,
				"Greenland" => -3,
				"Greenland Thule" => -4,
				"Greenland Scoresbysun" => -1,
				"Grenada" => -4,
				"Grenadines" => -4,
				"Guadeloupe" => -4,
				"Guam" => 10,
				"Guatemala" => -6,
				"Guinea" => 0,
				"Guyana" => -3,
				"Haiti" => -5,
				"Honduras" => -6,
				"Hong Kong" => 8,
				"Hungary" => 1,
				"Iceland" => 0,
				"India" => 5.5,
				"Indonesia Central" => 8,
				"Indonesia East" => 9,
				"Indonesia West" => 7,
				"Iran" => 3.5,
				"Iraq" => 3,
				"Ireland Republic of" => 0,
				"Israel" => 2,
				"Italy" => 1,
				"Jamaica" => -5,
				"Japan" => 9,
				"Johnston Island" => -10,
				"Jordan" => 2,
				"Kazakhstan" => 6,
				"Kenya" => 3,
				"Kiribati" => 12,
				"Korea Dem Republic of" => 9,
				"Korea Republic of" => 9,
				"Kusaie" => 12,
				"Kuwait" => 3,
				"Kwajalein" => -12,
				"Kyrgyzstan" => 5,
				"Laos" => 7,
				"Latvia" => 2,
				"Lebanon" => 2,
				"Leeward Islands" => -4,
				"Lesotho" => 2,
				"Liberia" => 0,
				"Libya" => 2,
				"Lithuania" => 2,
				"Luxembourg" => 1,
				"Macedonia" => 1,
				"Madagascar" => 3,
				"Madeira" => 0,
				"Malawi" => 2,
				"Malaysia" => 8,
				"Maldives" => 5,
				"Mali" => 0,
				"Mallorca Islands" => 1,
				"Malta" => 1,
				"Mariana Island" => 10,
				"Marquesas Islands" => -9.5,
				"Marshall Islands" => 12,
				"Martinique" => -4,
				"Mauritania" => 0,
				"Mauritius" => 4,
				"Mayotte" => 3,
				"Melilla" => 1,
				"Mexico" => -6,
				"Mexico Baja Calif Norte" => -8,
				"Mexico Nayarit" => -7,
				"Mexico Sinaloa" => -7,
				"Mexico Sonora" => -7,
				"Midway Island" => -11,
				"Moldova" => 2,
				"Moldovian Rep Pridnestrovye" => 2,
				"Monaco" => 1,
				"Mongolia" => 8,
				"Morocco" => 0,
				"Mozambique" => 2,
				"Myanmar (Burma)" => 6.5,
				"Namibia" => 1,
				"Nauru Republic of" => 12,
				"Nepal" => 5.75,
				"Netherlands" => 1,
				"Netherlands Antilles" => -4,
				"Nevis Montserrat" => -4,
				"New Caledonia" => 11,
				"New Hebrides" => 11,
				"New Zealand" => 12,
				"Nicaragua" => -6,
				"Niger" => 1,
				"Nigeria" => 1,
				"Niue Island" => -11,
				"Norfolk Island" => 11.5,
				"Northern Ireland" => 0,
				"Northern Mariana Islands" => 10,
				"Norway" => 1,
				"Oman" => 4,
				"Pakistan" => 5,
				"Palau" => 9,
				"Panama" => -5,
				"Papua New Guinea" => 10,
				"Paraguay" => -4,
				"Peru" => -5,
				"Philippines" => 8,
				"Pingelap" => 12,
				"Poland" => 1,
				"Ponape Island" => 11,
				"Portugal" => 1,
				"Principe Island" => 0,
				"Puerto Rico" => -4,
				"Qatar" => 3,
				"Reunion" => 4,
				"Romania" => 2,
				"Russian Federation zone eight" => 9,
				"Russian Federation zone eleven" => 12,
				"Russian Federation zone five" => 6,
				"Russian Federation zone four" => 5,
				"Russian Federation zone nine" => 10,
				"Russian Federation zone one" => 2,
				"Russian Federation zone seven" => 8,
				"Russian Federation zone six" => 7,
				"Russian Federation zone ten" => 11,
				"Russian Federation zone three" => 4,
				"Russian Federation zone two" => 4,
				"Rwanda" => 2,
				"Saba" => -4,
				"Samoa" => -11,
				"San Marino" => 1,
				"Sao Tome e Principe" => 0,
				"Saudi Arabia" => 3,
				"Scotland" => 0,
				"Senegal" => 0,
				"Seychelles" => 4,
				"Sierra Leone" => 0,
				"Singapore" => 8,
				"Slovakia" => 1,
				"Slovenia" => 1,
				"Society Island" => -10,
				"Solomon Islands" => 11,
				"Somalia" => 3,
				"South Africa" => 2,
				"Spain" => 1,
				"Sri Lanka" => 5.5,
				"St Christopher" => -4,
				"St Croix" => -4,
				"St Helena" => 0,
				"St John" => -4,
				"St Kitts Nevis" => -4,
				"St Lucia" => -4,
				"St Maarten" => -4,
				"St Pierre & Miquelon" => -3,
				"St Thomas" => -4,
				"St Vincent" => -4,
				"Sudan" => 2,
				"Suriname" => -3,
				"Swaziland" => 2,
				"Sweden" => 1,
				"Switzerland" => 1,
				"Syria" => 2,
				"Tahiti" => -10,
				"Taiwan" => 8,
				"Tajikistan" => 6,
				"Tanzania" => 3,
				"Thailand" => 7,
				"Togo" => 0,
				"Tonga" => 13,
				"Trinidad and Tobago" => -4,
				"Tuamotu Island" => -10,
				"Tubuai Island" => -10,
				"Tunisia" => 1,
				"Turkey" => 2,
				"Turkmenistan" => 5,
				"Turks and Caicos Islands" => -5,
				"Tuvalu" => 12,
				"Uganda" => 3,
				"Ukraine" => 2,
				"United Arab Emirates" => 4,
				"United Kingdom" => 0,			
		/*		"United States - Central" => -6,
				"United States - Eastern" => -5,
				"United States - Mountain" => -7,
				"United States - Arizona" => -7,
				"United States - Indiana East" => -5,
				"United States - Pacific" => -8,
				"United States - Alaska" => -9,
				"United States - Aleutian" => -10,
		*/
				"United States - Central" => -5,
				"United States - Eastern" => -4,
				"United States - Mountain" => -6,
				"United States - Arizona" => -6,
				"United States - Indiana East" => -4,
				"United States - Pacific" => -7,
				"United States - Alaska" => -8,
				"United States - Aleutian" => -9,

				"United States - Hawaii" => -10,
				"Uruguay" => -3,
				"Uzbekistan" => 5,
				"Vanuatu" => 11,
				"Vatican City" => 1,
				"Venezuela" => -4,
				"Vietnam" => 7,
				"Virgin Islands (US)" => -4,
				"Wake Island" => 12,
				"Wales" => 0,
				"Wallis and Futuna Islands" => 12,
				"Windward Islands" => -4,
				"Yemen" => 3,
				"Yugoslavia" => 1,
				"Zaire Kasai" => 2,
				"Zaire Kinshasa Mbandaka" => 1,
				"Zaire Haut Zaire" => 2,
				"Zaire Kivu" => 2,
				"Zaire Shaba" => 2,
				"Zambia" => 2,
				"Zimbabwe" => 2
			);
		}
		return self::$countryTzMap;
	}
}

/* End of file dateutil.php */
/* Location: ./application/libraries/dateutil.php */