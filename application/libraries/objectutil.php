<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ObjectUtil
{
	static function displayProperties($object)
	{
		foreach ($object as $key=>$value)
			echo $key . '=' . $value . ', ';
	}

	static function toString($object)
	{
		$text = "";
		foreach ($object as $key=>$value)
			$text .= $key . '=' . $value . ', ';
		return $text;
	}
}

/* End of file objectutil.php */
/* Location: ./application/libraries/objectutil.php */