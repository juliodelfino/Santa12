<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Lang extends CI_Lang
{
	function __construct()
	{
		parent::__construct();
    }
    
    function switch_to($idiom)
    {
        $CI =& get_instance();
        if(is_string($idiom) && $idiom != $CI->config->item('language'))
        {
            $CI->config->set_item('language', $idiom);
            $loaded = $this->is_loaded;
            $this->is_loaded = array();
                
            foreach($loaded as $file)
            {
                $this->load(str_replace('_lang.php','',$file));    
            }
        }
    }
}  

/* End of file App_Lang.php */
/* Location: ./system/application/libraries/App_Lang.php */