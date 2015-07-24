<?php

function session_check() {
	//get_instance()->session->sess_destroy();
	//var_dump(get_instance()->session->all_userdata());
	//var_dump(get_instance()->uri->uri_string());
	
	$uri = '';
	$uri_arr = explode('/', get_instance()->uri->uri_string());
	//var_dump(count($uri_arr));
	if(count($uri_arr) > 1)
	{
		$uri = $uri_arr[0];
	}
	else
	{
		$uri = get_instance()->uri->uri_string();
	}

	if($uri == 'login' || $uri == 'mobile' || $uri == 'api') return;
	
	if(!get_instance()->session->userdata('admin_id')){    //this is line 13
		redirect('/login');
	}
}

?>
