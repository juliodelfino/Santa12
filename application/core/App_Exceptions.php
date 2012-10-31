<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*source: http://erwin-atuli.com/codeigniter-error-handling-and-stack-tracing/ */
class App_Exceptions extends CI_Exceptions {
    public function __construct() {
        parent::__construct();
    }
    
    /* Enable this for stack trace purposes
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
        try {
            $str = parent::show_error($heading, $message, $template = 'error_general', $status_code = 500);
            throw new Exception($str);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $trace = "<h1>Call Trace</h1><pre>". $e->getTraceAsString(). "<pre>";
            //append our stack trace to the error message
            $err = str_replace('</div>', $trace.'</div>', $msg);
            echo $err;
        }
    }
    */
}

/* End of file App_Exceptions.php */
/* Location: ./system/application/libraries/App_Exceptions.php */