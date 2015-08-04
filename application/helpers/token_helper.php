<?php 
	function make_token($xid)
	{
		// make access token
		date_default_timezone_set('Asia/Seoul');
		$config = config_get();
		$extra_data = array(
			'service_key' => $config['service_key'],
			'version' => $config['version']
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
		$config = config_get();
		$access_token_data = msgpack_unpack(base64_decode($access_token));
		
		if($access_token_data['data']['service_key'] != $config['service_key'])
		{
			$result = 0;
		}
		else if($access_token_data['data']['version'] != $config['version'])
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