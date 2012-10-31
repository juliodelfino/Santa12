<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends App_Model {
	const TABLE_NAME = "users";
	
	function __construct()
    {
        parent::__construct();
    }
    
    public function getAll() {
        
		self::$udb->cache_on();
		
		$query = self::$udb->get('profiles');
        return $query->result();
    }
	
    function login($user) {     

        $data = (object)array("successful" => false, "errorMsg" => "");
        //Make sure login info was sent
        if($user->email == '' OR $user->password == '') {
        //	$data->errorMsg = vlang('login.error.please_login');
            return $data;
        }
        
        //Check against user table
        self::$udb->where('email', $user->email); 
        $query = self::$udb->get(self::TABLE_NAME);
        
        if ($query->num_rows() > 0) {
            $row = $query->row_array(); 
            
            //Check against password
            if(md5($user->password) != $row['password']) {
            	$data->errorMsg = vlang('login.error.pw_invalid');
                return $data;
            }
            
            //Remove the password field
            unset($row['password']);
            
            //Login was successful 
            $data->user = (object)$row;
            $data->successful = true;          
            return $data;
        } else {
            //No database result found			
			$data->errorMsg = vlang('login.error.user_pw_invalid');
			return $data;
		}   
    }

	public function get($id)
	{
		self::$udb->cache_on();
		$query = " SELECT * FROM " . self::TABLE_NAME . " WHERE userid = $id";
		$result = self::$udb->query($query);
		return $result->row();
	}
	
	public function insert($user)
	{
		self::$udb->insert(self::TABLE_NAME, $user);
		$user->userid = self::$udb->insert_id();
		return $user->userid;
	}
	
	public function update($id, $user)
	{	
		self::$udb->update(self::TABLE_NAME, $user, array('userid' => $id));
		self::$udb->cache_delete_all();
	}
	
	public function getByUsername($username)
	{
		self::$udb->cache_on();
		self::$udb->where("email", $username);
		$result = self::$udb->get(self::TABLE_NAME);
		return $result->row();
	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */