<?php 
	function get_service_id($service_key)
	{
		$return_value = array();
		$db_result = get_instance()->service_db_model->get_service_id($service_key);	
		if($db_result)
		{
			$return_value = array(
				"result" => "1",
				"data" => $db_result[0]['service_id']
			);
			
			return json_encode($return_value);
		}
		else
		{
			$return_value = array(
				"result" => "403"
			);
			
			return json_encode($return_value);
		}
	}
	
	function get_xid($access_token)
	{
		try
		{
			$xid = 0;
		
			$access_token_data = msgpack_unpack(base64_decode($access_token));
			$xid = $access_token_data['xid'];
			
			$db_result = get_instance()->member_db_model->is_exists_member($xid);
			if(!$db_result)
			{
				$result = 0;
			}
			
						
			return $xid;
		}
		catch(Exception $e)
		{
			var_dump($e->getMessage());
		}
	}


	function get_seller_id($access_token)
	{
		try
		{
			$result = 1;
			$seller_id = 0;
		
			$access_token_data = msgpack_unpack(base64_decode($access_token));
			$seller_id = $access_token_data['xid'];
			
			$db_result = get_instance()->seller_db_model->is_exists_seller($seller_id);
			if(!$db_result)
			{
				$seller_id = 0;
			}
			
						
			return $seller_id;
		}
		catch(Exception $e)
		{
			var_dump($e->getMessage());
		}
	}
	
	function get_seller_id_by_code($seller_code)
	{
		$return_value = 0;
		$db_result = get_instance()->seller_db_model->get_seller_id($seller_code);	
		if($db_result)
		{
			$return_value = $db_result[0];
		}
		
		return $return_value;
	}
	
	function check_header()
	{
		if(isset($_SERVER['HTTP_LANGUAGE_CODE']) && isset($_SERVER['HTTP_CLIENT_TYPE']))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	function set_req_log($api_name, $device, $parameter)
	{
		log_message('info', '/////////////////////////////////////////');
		
		log_message('info', 'API :: ' . $api_name);
		log_message('info', 'DEVICE :: ' . $device);
		log_message('info', 'REQUEST :: ' . $parameter);
	}

	function set_res_log($response)
	{
		log_message('info', 'RESPONSE :: ' . $response);
	}

	function set_xid_log($xid)
	{
		log_message('info', 'XID :: ' . $xid);
	}
	
	function set_err_log($description)
	{
		log_message('error', 'EXCEPTION!! :: ' . $description);
	}
?>