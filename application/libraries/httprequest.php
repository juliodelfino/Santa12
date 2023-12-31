<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
     
class HttpRequest {

    static function request($url, $referer = "", $_data = array(), $postGet = 'GET') {
     
		if ($postGet == 'POST')
		{
			// convert variables array to string:
			$data = array();    
			while(list($n,$v) = each($_data)){
				$data[] = "$n=$v";
			}    
			$data = implode('&', $data);
			// format --> test1=a&test2=b etc.
		}
     
        // parse the given URL
        $url = parse_url($url);
        if ($url['scheme'] != 'http') { 
            die('Only HTTP request are supported !');
        }
     
        // extract host and path:
        $host = $url['host'];
        $path = $url['path'];
     
        // open a socket connection on port 80
        $fp = fsockopen($host, 80);
     
		
		// send the request headers:
		fputs($fp, "POST $path HTTP/1.1\r\n");
		fputs($fp, "Host: $$host\r\n");
		fputs($fp, "Referer: $referer\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		if (!empty($data)) 
		{
			fputs($fp, "Content-length: ". strlen($data) ."\r\n");
		}
		fputs($fp, "Cookie: PHPSESSID=" . session_id() . "\r\n");
		
		fputs($fp, "Connection: close\r\n\r\n");
		
		if ($postGet == 'POST')
		{
			fputs($fp, $data);
		}
     
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
     
        // close the socket connection:
        fclose($fp);
     
        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);
     
        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';
     
        // return as array:
        return array($header, $content);
    }
}

/* End of file httprequest.php */
/* Location: ./application/libraries/httprequest.php */