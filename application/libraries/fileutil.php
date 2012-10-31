<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FileUtil
{
    static $extensions = array('jpg', 'jpeg', 'png', 'gif');
    
	static function getImages($dir)
	{		
		if (!file_exists($dir))
			return array();
		$files = array();
		foreach(get_filenames($dir) as $file) 
		{
		    if (in_array(pathinfo($file, PATHINFO_EXTENSION), self::$extensions))
		        $files[] = $file;
		}	
		return $files;
	}
	
	//Takes in a name of the <input type='file' /> element type, and returns
	//the upload status data (success, message, file_name, file_type).
	static function uploadFile($inputFile, $limitSize = false)
	{
	    $ci = get_instance();
        $uploadPath = 'images/uploads/';
		$config['upload_path'] = $uploadPath;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = true;		
		if ($limitSize) {
		    $config['max_size']	= '1024'; //1024KB
		}
		$data = new stdClass();
		
		$ci->load->library('upload', $config);
		
		if ($ci->upload->do_upload($inputFile)) {
			
			$data = (object)$ci->upload->data();
		    $data->success = true;
			$data->file_name = $uploadPath . $data->file_name;
		    $data->message = 'Upload successful.';
		}
		else {
			$data->success = false;
			$data->message = $ci->upload->display_errors('<div>', '</div>');
		}
		return $data;
	}
}

/* End of file fileutil.php */
/* Location: ./application/libraries/fileutil.php */