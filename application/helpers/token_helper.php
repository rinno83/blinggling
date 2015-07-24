<?php 
	function make_token($xid)
	{
		// make access token
		date_default_timezone_set('Asia/Seoul');
		$extra_data = array(
			'service_key' => get_instance()->config->item('service_key'),
			'version' => get_instance()->config->item('version')
		);
		$expire_date = date(strtotime('+7 day'));
		
		$access_token_data = array(
			'xid' => $xid,
			'expire' => $expire_date,
			'data' => $extra_data
		);
		
		return base64_encode(msgpack_pack($access_token_data));
	}
	
	function check_token($access_token)
	{
		$result = 1;
		$access_token_data = msgpack_unpack(base64_decode($access_token));
		
		if($access_token_data['data']['service_key'] != get_instance()->config->item('service_key'))
		{
			$result = 0;
		}
		else if($access_token_data['data']['version'] != get_instance()->config->item('version'))
		{
			$result = 0;
		}
		else
		{
			$result = 1;
		}
		
		return array('access_token' => $access_token, 'result' => $result);
	}
?>