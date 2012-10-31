<?php
		$ci = get_instance();
		$data['title'] = "Page Not Found";
		$data['content'] = $ci->load->view('error404', array(), true);
		$ci->load->view('layout/master', $data);

?>