<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EmailUtil
{
    static function php_email($recipient, $subject, $content) {
    
        ini_set("sendmail_from","no-reply@ntsp.nec.co.jp");
        ini_set("SMTP","SMTP");
	//	ini_set("username", 'admin@ngsf.com'); 
	//	ini_set("password", 'marvel'); 
  
        $headers = "From: no-reply@ntsp.nec.co.jp\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

              
        $result = mail($recipient, $subject,
            $content, $headers);
            
        return $result;
    }
	
    static function email_using_other_pc($recipient, $subject, $content) {
    
		$data = array(
	      'recipient' => $recipient,
	      'subject' => $subject,
	      'content' => $content
		);
		list($header, $content) = HttpRequest::request(
			"http://172.28.61.38/email/",
			"http://172.28.61.38/email/",
			$data, 'POST'
		);
		log_message('debug', 'email_using_other_pc reply message: ' . $content);
		$ok = stripos($content, 'OK');
		return $ok;
    }
    
    public static function sendReminderMail($recipient, $content)
    {
        $data = array('content' => nl2br($content), 'appUrl' => AppConfig::$url);
        return self::sendMail($recipient, 'reminder', $data);
    }
    
    public static function sendNewPostNotification($recipient, $notif)
    {
        $notifData = NotifType::parse($notif);
        
        $content = $notifData->notifText . ' Click here to view it:';
            
        $data = array('subject' => StringUtil::filterContent($notifData->notifText), 
		    'postLink' => AppConfig::$url . 'wishlist?id=' . $notifData->postid, 
            'content' => nl2br($content), 
        	'appUrl' => AppConfig::$url);
        return self::sendMail($recipient, 'new_post', $data);
    }
    
    public static function sendPostUpdateNotification($recipient, $notif)
    {
        $notifData = NotifType::parse($notif);
        
        $content = $notifData->notifText . ' Click here to view it:';
            
        $data = array('subject' => StringUtil::filterContent($notifData->notifText), 
		    'postLink' => AppConfig::$url . 'wishlist?id=' . $notifData->postid, 
            'content' => nl2br($content), 
        	'appUrl' => AppConfig::$url);
        return self::sendMail($recipient, 'post_update', $data);
    }
    
    public static function sendCommentNotification($recipient, $notif, $commentText)
    {
        $notifData = NotifType::parse($notif);
        
        $content = $notifData->notifText 
            . '<br>"' . $commentText . '"' 
            . '<br>Click here to view it:';
            
        $data = array('subject' => StringUtil::filterContent($notifData->notifText),  
		    'postLink' => AppConfig::$url . 'wishlist?id=' . $notifData->postid, 
            'content' => nl2br($content), 
        	'appUrl' => AppConfig::$url);
        return self::sendMail($recipient, 'comment', $data);
    }
	
	private static function replace($hashtable, $data)
	{
		$keys = array();
		$values = array();
		foreach($hashtable as $key => $value)
		{
			$keys[] = '$' . $key;
			$values[] = $value;
		}
		return str_replace($keys, $values, $data);
	}
	
	private static function getSubject($msgId)
	{
		$text = read_file(APPPATH . 'email/messages.xml');
		$xml = new SimpleXMLElement($text);
		$node = $xml->xpath('/mail/message[@id="' . $msgId . '"]');
		return $node[0]->subject;
	}
	
	private static function getMailHeader()
	{
		$text = read_file(APPPATH . 'email/messages.xml');
		$xml = new SimpleXMLElement($text);
		return $xml->header;
	}
	
	private static function sendMail($toEmail, $messageId, $data)
	{		
		$subject = self::getSubject($messageId);
		$subject = self::replace($data, $subject);
		
		$body = read_file(APPPATH . 'email/' . $messageId . '.html');
		$body = self::replace($data, $body);
		
		return self::email_using_other_pc($toEmail, $subject, $body);
	//	return self::ci_email($toEmail, $subject, $body);
	}
	
	static function ci_email($emailAddress, $subject, $body)
	{
		$ci = get_instance();
		$ci->load->library('email');

		$ci->email->clear();
		$config['mailtype'] = 'html';
		$config['protocol'] = 'smtp';
	//	$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'utf-8';
		$config['smtp_host'] = 'SMTP';
		$config['newline'] = '\r\n';
		$config['crlf'] = '\r\n';					
		$ci->email->initialize($config);
		$ci->email->from(AppConfig::$fromEmail, AppConfig::$webName);
		$ci->email->to($emailAddress);
	//	$ci->email->bcc(AppConfig::$adminEmail);
	//	$ci->email->reply_to(AppConfig::$contactUsEmail);
		
		$ci->email->subject($subject);
		$ci->email->message($body);

		$result = false;
		try 
		{
            $result = $ci->email->send();
		}
		catch(Exception $ex)
		{
            log_message('error', $ex->getMessage() . '\n Stacktrace: ' . $ex->getTraceAsString());
		} 
        if (!$result) {
            log_message('error', $ci->email->print_debugger());
        }
		return $result;
	}
}

/* End of file emailutil.php */
/* Location: ./application/libraries/emailutil.php */