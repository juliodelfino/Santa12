<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Model extends CI_Model
{
    static $udb;
    
	function __construct()
	{
		parent::__construct();
		self::loadDatabase();
    }
    
    static function loadDatabase() {
        
        $ci = get_instance();
        if (!isset( self::$udb)) {
             self::$udb = $ci->load->database('santa12', TRUE);
             log_message('notice', self::$udb->conn_id . ' at ' . time());
        }
    }
}  

/* End of file App_Model.php */
/* Location: ./system/application/libraries/App_Model.php */