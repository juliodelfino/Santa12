<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad_model extends App_Model {
	const TABLE_NAME = "ads";
	
	function __construct()
    {
        parent::__construct();
    }
    
    public function getAll() {
        
		self::$udb->cache_on();
		
		$query = self::$udb->get(self::TABLE_NAME);
        return $query->result();
    }
    
    public function query($query) {
        
        $query = self::$udb->query($query); 
        $data = new stdClass();       
        $data->fields = $query->list_fields();
        $data->results = $query->result();
        return $data;
    }
}

/* End of file ad_model.php */
/* Location: ./application/models/ad_model.php */